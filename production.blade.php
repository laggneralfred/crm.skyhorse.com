<div>
    <h2 class="text-xl font-bold mb-4">Production Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded border">
            <p><strong>Total MWh Generated:</strong> {{ $project->TotalMWhGenerated }}</p>
            <p><strong>Average Capacity Factor:</strong> {{ $project->AverageCapacityFactor }}%</p>
            <p><strong>Total Producing Months:</strong> {{ $project->TotalProducingMonths }}</p>
        </div>
        <div class="bg-white p-4 rounded border">
            <p><strong>First 12 Month MWh:</strong> {{ $project->First12MonthMWhGenerated }}</p>
            <p><strong>First 12 Month Capacity:</strong> {{ $project->First12MonthCapacityFactor }}%</p>
            <p><strong>Last 12 Month MWh:</strong> {{ $project->Last12MonthMWhGenerated }}</p>
            <p><strong>Last 12 Month Capacity:</strong> {{ $project->Last12MonthCapacityFactor }}%</p>
        </div>
    </div>
</div>