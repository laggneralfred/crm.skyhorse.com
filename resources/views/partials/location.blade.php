<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 rounded shadow">
    <div>
        <p><strong>Address:</strong> {{ $project->Address }}</p>
        <p><strong>City:</strong> {{ $project->City }}</p>
        <p><strong>Zip Code:</strong> {{ $project->ZipCode }}</p>
        <p><strong>Latitude:</strong> {{ $project->Latitude }}</p>
    </div>
    <div>
        <p><strong>State:</strong> {{ $project->StateProvince }}</p>
        <p><strong>County:</strong> {{ $project->County }}</p>
        <p><strong>Longitude:</strong> {{ $project->Longitude }}</p>
    </div>
</div>
