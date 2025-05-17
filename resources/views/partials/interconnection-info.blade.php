
<div x-show="activeTab === 'interconnection-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Interconnection Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PointOfInterconnection:</span> {{ $project->PointOfInterconnection }}</p>
                
                <p><span class="font-semibold">InterconnectionPrimaryVoltage:</span> {{ $project->InterconnectionPrimaryVoltage }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">InterconnectionSecondaryVoltage:</span> {{ $project->InterconnectionSecondaryVoltage }}</p>
                
                <p><span class="font-semibold">InterconnectionENVOwner:</span> {{ $project->InterconnectionENVOwner }}</p>
                
            </div>
            
        </div>
    </div>
</div>