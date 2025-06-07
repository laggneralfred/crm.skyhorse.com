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

        $existing = Query::where('user_id', auth()->id())
            ->where('question', $prompt)
            ->first();

        if ($existing && $existing->sql) {
            $sql = $existing->sql;
            $cheatSheet = $existing->cheatsheet ?? '';
        } else {
            $messages = $this->buildMessagesFromPrompt($prompt);
            $systemPrompt = $messages[0]['content'];
            $cheatSheet = Prompt::where('name', 'default-cheatsheet')->value('content') ?? '';

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
                    [
                        'sql' => $sql,
                        'cheatsheet' => $cheatSheet,
                    ]
                );
            } else {
                throw new \Exception($responseText ?: '⚠️ Could not generate valid SQL.');
            }
        }

        $this->validateFieldsInSql($sql, $cheatSheet);
        $result = DB::select($sql);

        $tableData = collect($result)->map(function ($row) {
            $row = (array) $row;
            $normalized = [];
            foreach ($row as $key => $value) {
                $normalized[strtolower($key)] = $value;
            }
            return $normalized;
        });

        $mapData = $tableData->filter(fn($row) =>
            isset($row['latitude'], $row['longitude'])
        )->values();

        return compact('tableData', 'mapData', 'sql', 'responseText');
    }

    public function buildMessagesFromPrompt(string $prompt): array
    {
        $systemPromptRaw = Prompt::where('name', 'default-system')->value('content');
        $cheatSheet = Prompt::where('name', 'default-cheatsheet')->value('content');

        if (!$systemPromptRaw || !$cheatSheet) {
            throw new \Exception('⚠️ Missing system prompt or cheat sheet in database.');
        }

        $systemPrompt = str_replace('{{cheatSheet}}', $cheatSheet, $systemPromptRaw);

        return [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $prompt],
        ];
    }

    public function validateFieldsInSql(string $sql, string $cheatSheet): void
    {
        if (config('app.debug')) return;

        $validFields = $this->extractValidFieldsFromCheatSheet($cheatSheet);
        preg_match_all('/"([a-zA-Z0-9_]+)"\."([a-zA-Z0-9_]+)"/', $sql, $matches, PREG_SET_ORDER);
        $errors = [];

        foreach ($matches as $match) {
            $field = "\"{$match[1]}\".\"{$match[2]}\"";
            if (!in_array($field, $validFields)) {
                $errors[] = $field;
            }
        }


        if (!empty($errors)) {
            throw new \Exception("⚠️ Invalid field(s) detected in SQL: " . implode(', ', $errors));
        }
    }

    private function extractValidFieldsFromCheatSheet(string $cheatSheet): array
    {
        $fields = [];

        preg_match_all('/^([A-Za-z0-9_]+):/m', $cheatSheet, $topLevel);
        foreach ($topLevel[1] as $field) {
            $fields[] = '"solar_projects"."'.$field.'"';
        }

        preg_match_all('/-\s+"([a-zA-Z0-9_]+)\."([a-zA-Z0-9_]+)"/', $cheatSheet, $nested);
        foreach ($nested[1] as $i => $table) {
            $field = $nested[2][$i];
            $fields[] = "\"$table"."$field\"";
        }

        return array_unique($fields);
    }

    private function extractSql(string $response): string
    {
        // Preferred: extract SQL inside fenced ```sql ... ```
        if (preg_match('/```sql\s*(SELECT .*?)```/is', $response, $matches)) {
            return trim($matches[1]);
        }

        // Fallback: find any clean SELECT ... FROM ... ;
        if (preg_match('/(SELECT\s+.*?FROM\s+.*?;)/is', $response, $matches)) {
            return trim($matches[1]);
        }

        // Last fallback: SELECT ... FROM ... without ;
        if (preg_match('/(SELECT\s+.*?FROM\s+.*?)(\n|$)/is', $response, $matches)) {
            return trim($matches[1]);
        }

        throw new \Exception('⚠️ No valid SQL statement found in the AI response.');
    }

    public function runStoredQuery(Query $query, OpenAIQueryService $queryService)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $textResponse = null;
        $tableData = collect();
        $mapData = collect();

        try {
            $cheatsheet = $query->cheatsheet ?? Prompt::where('name', 'default-cheatsheet')->value('content') ?? '';
            $queryService->validateFieldsInSql($query->sql, $cheatsheet);

            $result = DB::select($query->sql);

            $tableData = collect($result)->map(fn($row) => (array) $row);
            $mapData = $tableData->filter(fn($row) =>
            isset($row['latitude'], $row['longitude'])
            )->values();
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        $pastQueries = Query::where('user_id', auth()->id())
            ->orderByDesc('favorited')
            ->latest()
            ->take(10)
            ->get();

        return view('openai.dashboard', [
            'prompt' => $query->question,
            'sql' => $query->sql,
            'textResponse' => $textResponse,
            'tableData' => $tableData,
            'mapData' => $mapData,
            'pastQueries' => $pastQueries,
        ]);
    }


}
