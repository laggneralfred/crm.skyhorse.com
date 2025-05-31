<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Query;

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
            $cheatSheetPath = storage_path('app/solar_cheatsheet.txt');
            $cheatSheet = file_exists($cheatSheetPath)
                ? file_get_contents($cheatSheetPath)
                : '⚠️ Cheat sheet file not found.';

            $messages = [
                [
                    'role' => 'system',
                    'content' => <<<EOT
You are an AI database assistant for a PostgreSQL database with three tables: "solar_projects", "project_contacts", and "key_company_contacts".

RULES YOU MUST FOLLOW:
- All table and field names are case-sensitive and MUST match exactly.
- Wrap ALL table and field names in double quotes, like "solar_projects"."ProjectName".
- NEVER invent field names or use lowercase versions of existing fields.
- ALWAYS fully qualify every field with its table name.
- "StateProvince" ONLY comes from the "solar_projects" table.
- Table header names in result should be standard expressions, like "Contact name" instead of contactname.
- Use SQL aliases with double quotes only, e.g., AS "Contact name", not single quotes.
- Whenever the "solar_projects" table is involved in the query, you MUST include the "Latitude" and "Longitude" fields in the SELECT clause and alias them as lowercase `latitude` and `longitude`, even if the user did not request them. These fields are required for mapping purposes.

Below is the list of valid field names:
$cheatSheet
EOT
                ],
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

        $this->validateFieldsInSql($sql);
        $result = DB::select($sql);

        // Lowercase all result keys
        $tableData = collect($result)->map(function ($row) {
            $row = (array) $row;
            $normalized = [];
            foreach ($row as $key => $value) {
                $normalized[strtolower($key)] = $value;
            }
            return $normalized;
        });

        // Only rows with lat/lon will show on map
        $mapData = $tableData->filter(fn($row) =>
        isset($row['latitude'], $row['longitude'])
        )->values();

        // Log prompt + SQL
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
        // You can add your field whitelist logic here
    }
}
