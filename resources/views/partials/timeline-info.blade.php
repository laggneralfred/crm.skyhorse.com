
<div x-show="activeTab === 'timeline-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Timeline Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">ConstructionDate:</span> {{ $project->ConstructionDate }}</p>
                
                <p><span class="font-semibold">FirstQueueDate:</span> {{ $project->FirstQueueDate }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">FirstYearPower:</span> {{ $project->FirstYearPower }}</p>
                
                <p><span class="font-semibold">LastPowerDate:</span> {{ $project->LastPowerDate }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">LastYearPower:</span> {{ $project->LastYearPower }}</p>
                
                <p><span class="font-semibold">RepoweringDate:</span> {{ $project->RepoweringDate }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">RepoweringYear:</span> {{ $project->RepoweringYear }}</p>
                
                <p><span class="font-semibold">SuspendedDate:</span> {{ $project->SuspendedDate }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">ProjectLastUpdatedDate:</span> {{ $project->ProjectLastUpdatedDate }}</p>
                
            </div>
            
        </div>
    </div>
</div>