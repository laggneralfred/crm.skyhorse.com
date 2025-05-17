
<div x-show="activeTab === 'grid-market-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Grid Market Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">ISO:</span> {{ $project->ISO }}</p>
                
                <p><span class="font-semibold">ISOTerritory:</span> {{ $project->ISOTerritory }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">NERC Region:</span> {{ $project->NERC_Region }}</p>
                
                <p><span class="font-semibold">QueueNumber:</span> {{ $project->QueueNumber }}</p>
                
            </div>
            
        </div>
    </div>
</div>