<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class OpenAIQueryController extends Controller
{
    public function index()
    {
        return view('openai.query');
    }

    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');

        $cheatSheetPath = storage_path('app/solar_cheatsheet.txt');
        $cheatSheet = file_exists($cheatSheetPath)
            ? file_get_contents($cheatSheetPath)
            : '⚠️ Cheat sheet file not found.';

        $messages = [
            [
                'role' => 'system',
                'content' => <<<EOT
You are an AI database assistant for a PostgreSQL table called "solar_projects".

Below is the list of valid field names you MUST use when writing SQL queries.
Only use fields from this list. Do not ask for more field information. Always output a complete SELECT statement.

IMPORTANT:
- Table name is always "solar_projects"
- Field names are case-sensitive and must match exactly.
- Wrap all table and field names in double quotes.
- Do NOT invent fields or use unspecified ones.

Here is the list of valid fields and their meanings:
$cheatSheet
EOT
            ],
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ];


        $responseText = '';
        $error = null;
        $sql = null;
        $result = null;
        $textResponse = null;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => $messages,
                    'temperature' => 0,
                ]);

            if ($response->successful()) {
                $responseText = $response->json()['choices'][0]['message']['content'] ?? '';
            } else {
                $error = 'OpenAI API error: ' . $response->body();
            }
        } catch (\Exception $e) {
            $error = 'Request failed: ' . $e->getMessage();
        }

        if ($error) {
            return view('openai.query', [
                'prompt' => $prompt,
                'sql' => null,
                'result' => null,
                'textResponse' => '⚠️ ' . $error,
            ]);
        }

        $sql = $this->extractSql($responseText ?? '');

        if (empty($sql) || !str_starts_with(strtolower(trim($sql)), 'select')) {
            return view('openai.query', [
                'prompt' => $prompt,
                'sql' => null,
                'result' => null,
                'textResponse' => $responseText ?: '⚠️ Sorry, I could not generate a valid SQL query from your request.',
            ]);
        }

        try {
            $this->validateFieldsInSql($sql);
            $result = DB::select($sql);
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        return view('openai.query', [
            'prompt' => $prompt,
            'sql' => $sql,
            'result' => $result,
            'textResponse' => $textResponse,
        ]);
    }

    private function extractSql($openaiResponse)
    {
        if (preg_match('/```sql(.*?)```/s', $openaiResponse, $matches)) {
            return trim($matches[1]);
        }

        return trim($openaiResponse);
    }

    private function validateFieldsInSql($sql)
    {
        // ✅ List of allowed fields
        $allowedFields = [
            'Developer', 'Owner', 'FormerOwner', 'ProjectName', 'ProjectType',
            'ProjectCapacityMW', 'CurrentOperatingCapacityMW', 'ProjectStatus',
            'ProjectDurationhours', 'CommunitySolar', 'CoLocatedProject', 'BatteryType',
            'PowerPurchaser', 'PowerPurchaseAgreementCapacityMW', 'PowerPurchaseAgreementYears',
            'Supplier', 'EPC', 'FirstPowerDate', 'FirstYearPower', 'ConstructionDate',
            'RepoweringDate', 'Country', 'StateProvince', 'City', 'ZipCode', 'Address',
            'Latitude', 'Longitude', 'EstimatedCoordinates', 'EstimateSource',
            'TotalProducingMonths', 'TotalMWhGenerated', 'AverageCapacityFactor',
            'ISO', 'QueueNumber', 'FirstQueueDate'
        ];

        // ✅ List of allowed tables
        $allowedTables = [
            'solar_projects',
            'project_contacts',
            'key_company_contacts',
        ];

        // ✅ Extract only table.field references like "solar_projects"."ProjectName"
        preg_match_all('/"(\w+)"\."(\w+)"/', $sql, $matches);
        $tablesUsed = $matches[1] ?? [];
        $fieldsUsed = $matches[2] ?? [];

        $allowedFieldsLower = array_map('strtolower', $allowedFields);
        $allowedTablesLower = array_map('strtolower', $allowedTables);

        foreach ($fieldsUsed as $field) {
            if (!in_array(strtolower($field), $allowedFieldsLower)) {
                throw new \Exception("Invalid field detected in SQL: \"$field\"");
            }
        }

        foreach ($tablesUsed as $table) {
            if (!in_array(strtolower($table), $allowedTablesLower)) {
                throw new \Exception("Invalid table detected in SQL: \"$table\"");
            }
        }
    }

}
