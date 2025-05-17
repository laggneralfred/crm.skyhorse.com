<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HubspotSyncController extends Controller
{
    public function syncCaliforniaContacts()
    {
        $this->createCustomProperties();

        $token = config('services.hubspot.token');
        $headers = [
            'Authorization' => "Bearer $token",
            'Content-Type'  => 'application/json',
        ];

        $projects = \App\Models\SolarProject::where('StateProvince', 'OR')->take(20)->get();
        $synced = [];

        foreach ($projects as $project) {
            $envId = $project->ENVProjectID;
            $safeEmail = 'project-' . md5($envId) . '@crm.skyhorseai.com';

            // Search by email
            $search = Http::withHeaders($headers)->post(
                'https://api.hubapi.com/crm/v3/objects/contacts/search',
                [
                    'filterGroups' => [[
                        'filters' => [[
                            'propertyName' => 'email',
                            'operator' => 'EQ',
                            'value' => $safeEmail,
                        ]]
                    ]],
                    'properties' => ['email']
                ]
            );

            $contactId = $search->json('results.0.id') ?? null;

            $popupUrl = "https://crm.skyhorseai.com/hubspot-handler?ENVProjectID=" . urlencode($envId);
            $popupLinkHtml = "<a href=\"{$popupUrl}\" target=\"_blank\">Open Solar Project ↗︎</a>";

            $payload = [
                'properties' => [
                    // HubSpot default fields
                    'email'     => $safeEmail,
                    'firstname' => 'Solar Project',
                    'lastname'  => $project->ProjectName ?? 'Unnamed',
                    'address'   => $project->Address,
                    'city'      => $project->City,
                    'state'     => $project->StateProvince,
                    'zip'       => $project->Zip,
                    'phone'     => $project->Phone ?? null,

                    // Custom fields
                    'envprojectid'             => $envId,
                    'projectname'              => $project->ProjectName,
                    'longitude'                => (string) $project->Longitude,
                    'latitude'                 => (string) $project->Latitude,
                    'owner'                    => $project->Owner,
                    'solar_project_popup_link' => $popupLinkHtml,
                ]
            ];

            if ($contactId) {
                $update = Http::withHeaders($headers)->patch(
                    "https://api.hubapi.com/crm/v3/objects/contacts/{$contactId}",
                    $payload
                );
                \Log::info("Updated contact {$contactId}", $update->json());
            } else {
                $create = Http::withHeaders($headers)->post(
                    'https://api.hubapi.com/crm/v3/objects/contacts',
                    $payload
                );
                \Log::info('Created contact', $create->json());
                $contactId = $create->json('id') ?? null;
            }

            // Add note with popup link
            if ($contactId) {
                $notePayload = [
                    'properties' => [
                        'hs_note_body' => "🔗 {$popupLinkHtml}",
                        'hs_timestamp' => now()->toIso8601String(),
                    ],
                    'associations' => [[
                        'to' => ['id' => $contactId],
                        'types' => [[
                            'associationCategory' => 'HUBSPOT_DEFINED',
                            'associationTypeId' => 202
                        ]]
                    ]]
                ];

                $note = Http::withHeaders($headers)->post(
                    'https://api.hubapi.com/crm/v3/objects/notes',
                    $notePayload
                );
                \Log::info("Note added to contact {$contactId}", $note->json());
            }

            $synced[] = [
                'project'     => $project->ProjectName,
                'env_id'      => $envId,
                'hubspot_id'  => $contactId,
            ];
        }

        return response()->json([
            'status' => 'sync_complete',
            'synced' => $synced,
        ]);
    }

    private function createCustomProperties()
    {
        $token = config('services.hubspot.token');
        $endpoint = 'https://api.hubapi.com/crm/v3/properties/contacts';

        $fields = [
            'envprojectid'             => 'ENVProjectID',
            'projectname'              => 'Project Name',
            'owner'                    => 'Owner',
            'stateprovince'            => 'State/Province',
            'city'                     => 'City',
            'zip'                      => 'ZIP Code',
            'address'                  => 'Address',
            'longitude'                => 'Longitude',
            'latitude'                 => 'Latitude',
            'solar_project_popup_link' => 'Solar Project Popup Link',
        ];

        foreach ($fields as $name => $label) {
            $check = Http::withToken($token)->get("$endpoint/$name");

            if ($check->successful()) {
                continue;
            }

            $payload = [
                'name'        => $name,
                'label'       => $label,
                'description' => "Auto-created for solar project syncing",
                'groupName'   => 'contactinformation',
                'type'        => 'string',
                'fieldType'   => 'text',
                'formField'   => false,
            ];

            if ($name === 'solar_project_popup_link') {
                $payload['displayHint'] = 'html';
            }

            $resp = Http::withToken($token)->post($endpoint, $payload);
            \Log::info("Property [$name] creation response", $resp->json());
        }
    }
}
