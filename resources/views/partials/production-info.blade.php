
<div x-show="activeTab === 'production-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Production Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">TotalProducingMonths:</span> {{ $project->TotalProducingMonths }}</p>
                
                <p><span class="font-semibold">Total MWhGenerated:</span> {{ $project->TotalMWhGenerated }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First3Month MWhGenerated:</span> {{ $project->First3MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First3MonthCapacityFactor:</span> {{ $project->First3MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First6Month MWhGenerated:</span> {{ $project->First6MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First6MonthCapacityFactor:</span> {{ $project->First6MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First9Month MWhGenerated:</span> {{ $project->First9MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First9MonthCapacityFactor:</span> {{ $project->First9MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First12Month MWhGenerated:</span> {{ $project->First12MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First12MonthCapacityFactor:</span> {{ $project->First12MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First24Month MWhGenerated:</span> {{ $project->First24MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First24MonthCapacityFactor:</span> {{ $project->First24MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">First36Month MWhGenerated:</span> {{ $project->First36MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">First36MonthCapacityFactor:</span> {{ $project->First36MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last3Month MWhGenerated:</span> {{ $project->Last3MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last3MonthCapacityFactor:</span> {{ $project->Last3MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last6Month MWhGenerated:</span> {{ $project->Last6MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last6MonthCapacityFactor:</span> {{ $project->Last6MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last9Month MWhGenerated:</span> {{ $project->Last9MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last9MonthCapacityFactor:</span> {{ $project->Last9MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last12Month MWhGenerated:</span> {{ $project->Last12MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last12MonthCapacityFactor:</span> {{ $project->Last12MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last24Month MWhGenerated:</span> {{ $project->Last24MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last24MonthCapacityFactor:</span> {{ $project->Last24MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">Last36Month MWhGenerated:</span> {{ $project->Last36MonthMWhGenerated }}</p>
                
                <p><span class="font-semibold">Last36MonthCapacityFactor:</span> {{ $project->Last36MonthCapacityFactor }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">AverageCapacityFactor:</span> {{ $project->AverageCapacityFactor }}</p>
                
            </div>
            
        </div>
    </div>
</div>