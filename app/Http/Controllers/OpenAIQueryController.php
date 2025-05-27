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

    public function dashboard()
    {
        return view('openai.dashboard');
    }

    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');
        $export = $request->has('export');

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

Below is the list of valid field names:
$cheatSheet
EOT
            ],
            [
                'role' => 'user',
                'content' => $prompt,
            ],
        ];

        $responseText = '';
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

            $responseText = $response->json()['choices'][0]['message']['content'] ?? '';
        } catch (\Exception $e) {
            return back()->with([
                'prompt' => $prompt,
                'textResponse' => '⚠️ Request failed: ' . $e->getMessage(),
            ]);
        }

        $sql = $this->extractSql($responseText);

        if (empty($sql) || !str_starts_with(strtolower(trim($sql)), 'select')) {
            return back()->with([
                'prompt' => $prompt,
                'sql' => null,
                'result' => null,
                'textResponse' => $responseText ?: '⚠️ Sorry, could not generate a valid SQL query.',
            ]);
        }

        try {
            $this->validateFieldsInSql($sql);
            $result = DB::select($sql);
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        if ($export && !empty($result)) {
            $filename = 'solar_query_' . now()->format('Ymd_His') . '.csv';
            $rows = collect($result);

            return response()->stream(function () use ($rows) {
                $out = fopen('php://output', 'w');
                if ($rows->isNotEmpty()) {
                    fputcsv($out, array_keys((array) $rows->first()));
                    foreach ($rows as $row) {
                        fputcsv($out, (array) $row);
                    }
                }
                fclose($out);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }

        return view('openai.dashboard', [
            'prompt' => $prompt,
            'sql' => $sql,
            'result' => $result,
            'textResponse' => $textResponse,
        ]);
    }

    private function extractSql($response)
    {
        if (preg_match('/```sql(.*?)```/s', $response, $matches)) {
            return trim($matches[1]);
        }

        return trim($response);
    }

    private function validateFieldsInSql($sql)
    {
        $allowedTables = [
            'solar_projects',
            'project_contacts',
            'key_company_contacts',
        ];

        $allowedFields = [
            'Developer', 'Owner', 'FormerOwner', 'ProjectName', 'ProjectType',
            'ProjectCapacityMW', 'CurrentOperatingCapacityMW', 'ProjectStatus',
            'ProjectDurationhours', 'CommunitySolar', 'CoLocatedProject', 'BatteryType',
            'PowerPurchaser', 'PowerPurchaseAgreementCapacityMW', 'PowerPurchaseAgreementYears',
            'Supplier', 'EPC', 'FirstPowerDate', 'FirstYearPower', 'ConstructionDate',
            'RepoweringDate', 'Country', 'StateProvince', 'City', 'ZipCode', 'Address',
            'Latitude', 'Longitude', 'EstimatedCoordinates', 'EstimateSource',
            'TotalProducingMonths', 'TotalMWhGenerated', 'AverageCapacityFactor',
            'ISO', 'QueueNumber', 'FirstQueueDate', 'ENVProjectID',
            'contactname', 'contactemail', 'contactphone',
            'keycontactname', 'keycontactmail', 'keycontactphone', 'keycontacttitle',
            'envprojectid'
        ];

        preg_match_all('/"(\w+)"\."(\w+)"/', $sql, $matches);
        $tablesUsed = $matches[1] ?? [];
        $fieldsUsed = $matches[2] ?? [];

        foreach ($tablesUsed as $table) {
            if (!in_array(strtolower($table), array_map('strtolower', $allowedTables))) {
                throw new \Exception("Invalid table detected in SQL: \"$table\"");
            }
        }

        foreach ($fieldsUsed as $field) {
            if (!in_array(strtolower($field), array_map('strtolower', $allowedFields))) {
                throw new \Exception("Invalid field detected in SQL: \"$field\"");
            }
        }
    }
}
