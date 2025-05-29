<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Query;

class OpenAIQueryController extends Controller
{
    public function index()
    {
        return view('openai.query');
    }

    public function dashboard()
    {
        $pastQueries = Query::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('openai.dashboard', [
            'prompt' => null,
            'sql' => null,
            'result' => null,
            'textResponse' => null,
            'pastQueries' => $pastQueries,
        ]);
    }

    public function generate(Request $request)
    {
        $prompt = trim($request->input('prompt'));
        $export = $request->has('export');

        $result = null;
        $sql = null;
        $textResponse = null;

        // Try to retrieve previously saved query
        $existing = Query::where('user_id', auth()->id())
            ->where('question', $prompt)
            ->first();

        if ($existing && $existing->sql) {
            // Use saved SQL
            $sql = $existing->sql;
        } else {
            // Load cheat sheet
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
                ['role' => 'user', 'content' => $prompt],
            ];

            try {
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
                    return back()->with([
                        'prompt' => $prompt,
                        'sql' => null,
                        'result' => null,
                        'textResponse' => $responseText ?: '⚠️ Sorry, could not generate a valid SQL query.',
                    ]);
                }
            } catch (\Exception $e) {
                return back()->with([
                    'prompt' => $prompt,
                    'textResponse' => '⚠️ Request failed: ' . $e->getMessage(),
                ]);
            }
        }

        // Validate and run SQL
        try {
            $this->validateFieldsInSql($sql);
            $result = DB::select($sql);
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        // Handle CSV export
        if ($export && !empty($result)) {
            $filename = 'solar_query_' . now()->format('Ymd_His') . '.csv';
            return response()->stream(function () use ($result) {
                $out = fopen('php://output', 'w');
                fputcsv($out, array_keys((array) $result[0]));
                foreach ($result as $row) {
                    fputcsv($out, (array) $row);
                }
                fclose($out);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        }

        // Load past queries for display
        $pastQueries = Query::where('user_id', auth()->id())
            ->orderByDesc('favorited')
            ->latest()
            ->take(10)
            ->get();

        return view('openai.dashboard', [
            'prompt' => $prompt,
            'sql' => $sql,
            'result' => $result,
            'textResponse' => $textResponse,
            'pastQueries' => $pastQueries,
        ]);
    }

    private function extractSql($response)
    {
        if (preg_match('/```sql(.*?)```/s', $response, $matches)) {
            return trim($matches[1]);
        }

        return trim($response);
    }
    public function runStoredQuery(Query $query)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $result = null;
        $textResponse = null;

        try {
            $this->validateFieldsInSql($query->sql);
            $result = DB::select($query->sql);
        } catch (\Exception $e) {
            $textResponse = '⚠️ ' . $e->getMessage();
        }

        $pastQueries = Query::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('openai.dashboard', [
            'prompt' => $query->question,
            'sql' => $query->sql,
            'result' => $result,
            'textResponse' => $textResponse,
            'pastQueries' => $pastQueries,
        ]);
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
    public function clearQueries()
    {
        Query::where('user_id', auth()->id())->delete();

        return redirect()->route('query.dashboard')->with('message', 'All saved queries have been cleared.');
    }
    public function favorite(Query $query)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $query->favorited = !$query->favorited;
        $query->save();

        return back();
    }
    public function deleteQuery(Query $query)
    {
        if ($query->user_id !== auth()->id()) {
            abort(403);
        }

        $query->delete();

        return back()->with('message', 'Query deleted.');
    }


}
