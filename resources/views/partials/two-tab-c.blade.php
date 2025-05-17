
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Subgroup: Location -->
    <div class="col-span-2 border rounded p-4 bg-gray-50">
        <h2 class="text-lg font-semibold mb-4">Location</h2>
        <p><strong>Address:</strong> {{ $project->Address }}</p>
        <p><strong>City:</strong> {{ $project->City }}</p>
        <p><strong>State:</strong> {{ $project->StateProvince }}</p>
        <p><strong>Zip Code:</strong> {{ $project->ZipCode }}</p>
        <p><strong>Latitude:</strong> {{ $project->Latitude }}</p>
        <p><strong>Longitude:</strong> {{ $project->Longitude }}</p>
    </div>

    <!-- Subgroup: Production -->
    <div class="col-span-2 border rounded p-4 bg-gray-50">
        <h2 class="text-lg font-semibold mb-4">Production</h2>
        <p><strong>Total MWh Generated:</strong> {{ $project->TotalMWhGenerated }}</p>
        <p><strong>Average Capacity Factor:</strong> {{ $project->AverageCapacityFactor }}</p>
        <p><strong>First Year Power:</strong> {{ $project->FirstYearPower }}</p>
        <p><strong>Last Year Power:</strong> {{ $project->LastYearPower }}</p>
    </div>
</div>
