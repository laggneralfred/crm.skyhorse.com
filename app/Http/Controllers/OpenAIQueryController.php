<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Query;
use App\Services\OpenAIQueryService;

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



    public function generate(Request $request, OpenAIQueryService $queryService)
    {
        $prompt = trim($request->input('prompt'));
        $export = $request->has('export');

        try {
            $results = $queryService->generate($prompt);

            if ($export && !empty($results['tableData'])) {
                $filename = 'solar_query_' . now()->format('Ymd_His') . '.csv';
                return response()->streamDownload(function () use ($results) {
                    $output = fopen('php://output', 'w');
                    fputcsv($output, array_keys($results['tableData'][0]));
                    foreach ($results['tableData'] as $row) {
                        fputcsv($output, $row);
                    }
                    fclose($output);
                }, $filename);
            }

            $pastQueries = Query::where('user_id', auth()->id())
                ->orderByDesc('favorited')
                ->latest()
                ->take(10)
                ->get();

            return view('openai.dashboard', [
                'tableData' => $results['tableData'],
                'mapData' => $results['mapData'],
                'sql' => $results['sql'],
                'prompt' => $prompt,
                'textResponse' => $results['responseText'],
                'pastQueries' => $pastQueries,
            ]);
        } catch (\Exception $e) {
            $pastQueries = Query::where('user_id', auth()->id())
                ->orderByDesc('favorited')
                ->latest()
                ->take(10)
                ->get();

            return view('openai.dashboard', [
                'prompt' => $prompt,
                'sql' => null,
                'tableData' => [],
                'mapData' => [],
                'textResponse' => '⚠️ ' . $e->getMessage(),
                'pastQueries' => $pastQueries,
            ]);
        }
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

        $textResponse = null;
        $tableData = collect();
        $mapData = collect();

        try {
            $this->validateFieldsInSql($query->sql);
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
