
<div x-show="activeTab === 'other-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Other Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">BatteryType:</span> {{ $project->BatteryType }}</p>
                
                <p><span class="font-semibold">CommunitySolar:</span> {{ $project->CommunitySolar }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">CoLocatedProject:</span> {{ $project->CoLocatedProject }}</p>
                
                <p><span class="font-semibold">Country:</span> {{ $project->Country }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Dockets:</span> {{ $project->Dockets }}</p>
                
                <p><span class="font-semibold">EIA ID:</span> {{ $project->EIA_ID }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">EstimateSource:</span> {{ $project->EstimateSource }}</p>
                
                <p><span class="font-semibold">EstimatedCoordinates:</span> {{ $project->EstimatedCoordinates }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">UnitType:</span> {{ $project->UnitType }}</p>
                
                <p><span class="font-semibold">UnitSupplied:</span> {{ $project->UnitSupplied }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">created at:</span> {{ $project->created_at }}</p>
                
                <p><span class="font-semibold">updated at:</span> {{ $project->updated_at }}</p>
                
            </div>
            
        </div>
    </div>
</div>