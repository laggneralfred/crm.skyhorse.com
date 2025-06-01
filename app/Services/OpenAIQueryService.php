<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Query;
use App\Models\Prompt;

class OpenAIQueryService
{
    public function generate(string $prompt): array
    {
        $prompt = trim($prompt);
        $responseText = '';
        $sql = null;
        $tableData = collect();
        $mapData = collect();

        // Check for existing saved SQL
        $existing = Query::where('user_id', auth()->id())
            ->where('question', $prompt)
            ->first();

        if ($existing && $existing->sql) {
            $sql = $existing->sql;
        } else {
            // 🔄 Load system prompt and cheat sheet from DB
            $systemPromptRaw = Prompt::where('name', 'default-system')->value('content');

            $cheatSheet = Prompt::where('name', 'default-cheatsheet')->value('content');

            if (!$systemPromptRaw || !$cheatSheet) {
                throw new \Exception('⚠️ Missing system prompt or cheat sheet in database.');
            }

           // $systemPrompt = str_replace('$cheatSheet', $cheatSheet, $systemPromptRaw);
            //$systemPrompt = str_replace('{{cheatSheet}}', $cheatSheet, $systemPromptRaw);
            $systemPrompt = str_replace('{{cheatSheet}}', $cheatSheet, $systemPromptRaw);


            Log::info('[Prompt Used]', ['prompt' => $systemPrompt]);
            Log::info('[Cheatsheet Used]', ['prompt' => $cheatSheet]);
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt],
            ];

            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => $messages,
                    'temperature' => 0,
                ]);

            $responseText = $response->json()['choices'][0]['message']['content'] ?? '';
            $sql = $this->extractSql($responseText);

            if (!empty($sql) && str_starts_with(strtolower(trim($sql)), 'select')) {
                Query::updateOrCreate(
                    ['user_id' => auth()->id(), 'question' => $prompt],
                    ['sql' => $sql]
                );
            } else {
                throw new \Exception($responseText ?: '⚠️ Could not generate valid SQL.');
            }
        }

        // 🔍 Validate and run SQL
        $this->validateFieldsInSql($sql);
        $result = DB::select($sql);

        // 🔡 Normalize keys to lowercase
        $tableData = collect($result)->map(function ($row) {
            $row = (array) $row;
            $normalized = [];
            foreach ($row as $key => $value) {
                $normalized[strtolower($key)] = $value;
            }
            return $normalized;
        });

        // 🗺️ Filter for map
        $mapData = $tableData->filter(fn($row) =>
        isset($row['latitude'], $row['longitude'])
        )->values();

        // 📜 Log for debugging
        Log::info("OpenAI Prompt: $prompt");
        Log::info("Generated SQL: $sql");

        return compact('tableData', 'mapData', 'sql', 'responseText');
    }

    protected function extractSql(string $text): string
    {
        preg_match('/select .*?;/is', $text, $matches);
        return $matches[0] ?? throw new \Exception('No SQL found in AI response.');
    }

    protected function validateFieldsInSql(string $sql): void
    {
        // Add your SQL field whitelist or validation logic here if needed
    }
}
