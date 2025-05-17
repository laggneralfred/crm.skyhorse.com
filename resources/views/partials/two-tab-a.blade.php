
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">General Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Project Name:</strong> {{ $project->ProjectName }}</p>
            <p><strong>Status:</strong> {{ $project->ProjectStatus }}</p>
            <p><strong>Type:</strong> {{ $project->ProjectType }}</p>
            <p><strong>Capacity (MW):</strong> {{ $project->ProjectCapacityMW }}</p>
            <p><strong>Operating Capacity:</strong> {{ $project->CurrentOperatingCapacityMW }}</p>
            <p><strong>Unit Type:</strong> {{ $project->UnitType }}</p>
            <p><strong>Battery Type:</strong> {{ $project->BatteryType }}</p>
        </div>
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>First Power Date:</strong> {{ $project->FirstPowerDate }}</p>
            <p><strong>First Year Power:</strong> {{ $project->FirstYearPower }}</p>
            <p><strong>Total Producing Months:</strong> {{ $project->TotalProducingMonths }}</p>
            <p><strong>Total MWh Generated:</strong> {{ $project->TotalMWhGenerated }}</p>
            <p><strong>Avg. Capacity Factor:</strong> {{ $project->AverageCapacityFactor }}</p>
        </div>
    </div>

    <h2 class="text-xl font-bold my-6">Location</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>City:</strong> {{ $project->City }}</p>
            <p><strong>State:</strong> {{ $project->StateProvince }}</p>
            <p><strong>Zip Code:</strong> {{ $project->ZipCode }}</p>
            <p><strong>County:</strong> {{ $project->County }}</p>
            <p><strong>Country:</strong> {{ $project->Country }}</p>
        </div>
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Latitude:</strong> {{ $project->Latitude }}</p>
            <p><strong>Longitude:</strong> {{ $project->Longitude }}</p>
            <p><strong>Address:</strong> {{ $project->Address }}</p>
            <p><strong>Estimated Coords:</strong> {{ $project->EstimatedCoordinates }}</p>
        </div>
    </div>
</div>
