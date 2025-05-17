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
    
        $cheatSheet = <<<EOT
    Field explanations for the solar projects database:
    ( your cheat sheet here )
    EOT;
    
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an AI database assistant specialized in a PostgreSQL database about solar energy projects. The database uses case-sensitive field names. Always wrap all table names and column names in double quotes. Only use fields listed below. Do not invent or modify fields.' . "\n\n" . $cheatSheet
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
            $response = Http::withToken(env('OPENAI_API_KEY'))
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
    
        // Early return if OpenAI API failed
        if ($error) {
            return view('openai.query', [
                'prompt' => $prompt,
                'sql' => null,
                'result' => null,
                'textResponse' => '⚠️ ' . $error,
            ]);
        }
    
        // Extract SQL
        $sql = $this->extractSql($responseText ?? '');
    
        // If no proper SQL, fallback to showing text
        if (empty($sql) || !str_starts_with(strtolower(trim($sql)), 'select')) {
            return view('openai.query', [
                'prompt' => $prompt,
                'sql' => null,
                'result' => null,
                'textResponse' => $responseText ?: '⚠️ Sorry, I could not generate a valid SQL query from your request.',
            ]);
        }
    
        try {
            // Validate fields/tables before executing
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
        // List of allowed columns
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
    
        // List of allowed table names
        $allowedTables = [
            'solar_projects',
            'project_contacts',
            'key_company_contacts',
        ];
    
        // Extract all identifiers (everything inside double quotes)
        preg_match_all('/"([^"]+)"/', $sql, $matches);
        $identifiersUsed = $matches[1] ?? [];
    
        foreach ($identifiersUsed as $identifier) {
            $identifierLower = strtolower($identifier);
    
            $allowedFieldsLower = array_map('strtolower', $allowedFields);
            $allowedTablesLower = array_map('strtolower', $allowedTables);
    
            if (!in_array($identifierLower, $allowedFieldsLower) && !in_array($identifierLower, $allowedTablesLower)) {
                throw new \Exception("Invalid field or table detected in SQL: \"$identifier\"");
            }
        }
    }
    
}
