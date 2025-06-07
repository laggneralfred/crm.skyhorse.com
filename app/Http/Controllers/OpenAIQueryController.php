<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Query;
use App\Models\Prompt;
use App\Services\OpenAIQueryService;

class OpenAIQueryController extends Controller
{
    private array $allowedFields = [
        'Developer', 'Owner', 'FormerOwner', 'ProjectName', 'ProjectType',
        'ProjectCapacityMW', 'CurrentOperatingCapacityMW', 'ProjectStatus',
        'ProjectDurationhours', 'CommunitySolar', 'CoLocatedProject', 'BatteryType',
        'PowerPurchaser', 'PowerPurchaseAgreementCapacityMW', 'PowerPurchaseAgreementYears',
        'Supplier', 'EPC', 'FirstPowerDate', 'FirstYearPower', 'ConstructionDate',
        'RepoweringDate', 'Country', 'StateProvince', 'City', 'ZipCode', 'Address',
        'Latitude', 'Longitude', 'EstimatedCoordinates', 'EstimateSource',
        'TotalProducingMonths', 'TotalMWhGenerated', 'AverageCapacityFactor',
        'ISO', 'QueueNumber', 'FirstQueueDate', 'ENVProjectID',
        'envprojectid', 'contactname', 'contactemail', 'contactphone',
        'keycontactname', 'keycontactmail', 'keycontactphone', 'keycontacttitle',
    ];

    private array $allowedTables = [
        'solar_projects', 'project_contacts', 'key_company_contacts'
    ];

    public function index()
    {
        return view('openai.query', [
            'pastQueries' => $this->getPastQueries()
        ]);
    }

    public function dashboard()
    {
        return view('openai.dashboard', [
            'prompt' => null,
            'sql' => null,
            'result' => null,
            'textResponse' => null,
            'mapData' => [],
            'pastQueries' => $this->getPastQueries()
        ]);
    }

    public function generate(Request $request)
    {
        $prompt = $request->input('prompt');
        $export = $request->has('export');
        $cheatSheet = $this->getCheatSheet();
        $messages = $this->buildMessages($prompt, $cheatSheet);
        $responseText = $this->callOpenAI($messages, $error);

        if ($error) {
            return $this->renderDashboard($prompt, null, [], [], '⚠️ ' . $error);
        }

        $sql = $this->extractSql($responseText);

        if (empty($sql) || !str_starts_with(strtolower(trim($sql)), 'select')) {
            return $this->renderDashboard($prompt, $sql, [], [], '⚠️ Invalid SELECT statement.');
        }

        try {
            $this->validateFieldsInSql($sql);
            $result = DB::select($sql);
        } catch (\Exception $e) {
            return $this->renderDashboard($prompt, $sql, [], [], '⚠️ ' . $e->getMessage());
        }

        $tableData = collect($result)->map(fn($row) => (array)$row);
        $mapData = $tableData->filter(function ($row) {
            return isset($row['latitude'], $row['longitude']) &&
                is_numeric($row['latitude']) &&
                is_numeric($row['longitude']);
        })->values();

        if ($export && $tableData->isNotEmpty()) {
            return $this->exportCsv($tableData->all());
        }

        return $this->renderDashboard($prompt, $sql, $tableData, $mapData);
    }




    public function generateDashboard(Request $request)
    {
        $prompt = $request->input('prompt');
        $export = $request->input('export') ?? false;
        $data = $this->processPrompt($prompt);

        if ($export && !empty($data['result'])) {
            return $this->exportCsv($data['result']);
        }

        return view('openai.dashboard', $data + [
                'pastQueries' => $this->getPastQueries()
            ]);
    }

    private function extractSql(string $response): string
    {
        // Try to extract SQL block inside ```sql ... ```
        if (preg_match('/```sql(.*?)```/s', $response, $matches)) {
            $sql = trim($matches[1]);
        } else {
            $sql = trim($response);
        }

        // Patch SQL if "solar_projects" is involved but no lat/lng are present
        if (str_contains($sql, '"solar_projects"') &&
            !str_contains(strtolower($sql), 'as latitude') &&
            !str_contains(strtolower($sql), 'as longitude')) {

            $sql = preg_replace(
                '/select\s+/i',
                'SELECT "solar_projects"."Latitude" AS latitude, "solar_projects"."Longitude" AS longitude, ',
                $sql,
                1
            );
        }

        return $sql;
    }


    private function validateFieldsInSql(string $sql): void
    {
        preg_match_all('/"(\w+)"\."(\w+)"/', $sql, $matches);
        $tablesUsed = $matches[1] ?? [];
        $fieldsUsed = $matches[2] ?? [];

        $allowedFieldsLower = array_map('strtolower', $this->allowedFields);
        $allowedTablesLower = array_map('strtolower', $this->allowedTables);

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

    private function getCheatSheet(): string
    {
        $path = storage_path('app/solar_cheatsheet.txt');
        return file_exists($path)
            ? file_get_contents($path)
            : '⚠️ Cheat sheet file not found.';
    }

    private function buildMessages(string $prompt, string $cheatSheet): array
    {
        return [
            [
                'role' => 'system',
                'content' => <<<EOT
You are an AI assistant for a PostgreSQL database with 3 tables: "solar_projects", "project_contacts", and "key_company_contacts".

RULES:
- All table and field names are case-sensitive.
- Always wrap table and field names in double quotes, e.g., "solar_projects"."ProjectName".
- Never invent or guess field names.
- Always qualify fields with their table name.
- "StateProvince" must come from "solar_projects".

Valid fields and tables:
$cheatSheet
EOT
            ],
            ['role' => 'user', 'content' => $prompt],
        ];
    }

    private function callOpenAI(array $messages, &$error = null): string
    {
        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4',
                    'messages' => $messages,
                    'temperature' => 0,
                ]);

            if (!$response->successful()) {
                $error = 'OpenAI API error: ' . $response->body();
                return '';
            }

            return $response->json()['choices'][0]['message']['content'] ?? '';
        } catch (\Exception $e) {
            $error = 'Request failed: ' . $e->getMessage();
            return '';
        }
    }

    private function processPrompt(string $prompt): array
    {
        $cheatSheet = $this->getCheatSheet();
        $messages = $this->buildMessages($prompt, $cheatSheet);
        $responseText = $this->callOpenAI($messages, $error);

        if ($error) {
            return compact('prompt') + ['textResponse' => '⚠️ ' . $error];
        }

        $sql = $this->extractSql($responseText);
        if (empty($sql) || !str_starts_with(strtolower(trim($sql)), 'select')) {
            return compact('prompt', 'sql') + ['textResponse' => '⚠️ Invalid SELECT statement.'];
        }

        try {
            $this->validateFieldsInSql($sql);
            $result = DB::select($sql);
        } catch (\Exception $e) {
            return compact('prompt', 'sql') + ['textResponse' => '⚠️ ' . $e->getMessage()];
        }

        $tableData = collect($result)->map(fn($row) => (array)$row);
        $mapData = $tableData->filter(fn($row) => isset($row['latitude'], $row['longitude']))->values();

        return compact('prompt', 'sql', 'result', 'tableData', 'mapData');
    }

    private function exportCsv(array $rows)
    {
        $filename = 'solar_query_' . now()->format('Ymd_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv'];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            if (!empty($rows)) {
                fputcsv($out, array_keys((array)$rows[0]));
                foreach ($rows as $row) {
                    fputcsv($out, (array)$row);
                }
            }
            fclose($out);
        };

        return response()->stream($callback, 200, [
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ] + $headers);
    }

    public function favorite(Query $query)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $query->favorited = !$query->favorited;
        $query->save();

        return response()->json(['favorited' => $query->favorited]);
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
            $tableData = collect($result)->map(fn($row) => (array)$row);
            $mapData = $tableData->filter(fn($row) => isset($row['latitude'], $row['longitude']))->values();
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        return view('openai.dashboard', [
            'prompt' => $query->question,
            'sql' => $query->sql,
            'textResponse' => $textResponse,
            'tableData' => $tableData,
            'mapData' => $mapData,
            'pastQueries' => $this->getPastQueries(),
        ]);
    }

    public function deleteQuery(Query $query)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $query->delete();

        return response()->json(['deleted' => true]);
    }

    private function getPastQueries()
    {
        return Query::where('user_id', auth()->id())
            ->orderByDesc('favorited')
            ->latest()
            ->take(10)
            ->get();
    }
    private function renderDashboard($prompt, $sql, $tableData, $mapData, $textResponse = null)
    {
        return view('openai.dashboard', [
            'prompt' => $prompt,
            'sql' => $sql,
            'tableData' => $tableData,
            'mapData' => $mapData,
            'textResponse' => $textResponse,
            'pastQueries' => $this->getPastQueries()
        ]);
    }

}
