<div>
    <h2 class="text-xl font-bold mb-4">Developer Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded border">
            <p><strong>Developer:</strong> {{ $project->Developer }}</p>
            <p><strong>HQ Address:</strong> {{ $project->ENVDeveloperHQAddress }}</p>
            <p><strong>City:</strong> {{ $project->ENVDeveloperHQCity }}</p>
            <p><strong>State:</strong> {{ $project->ENVDeveloperHQState }}</p>
            <p><strong>Zip Code:</strong> {{ $project->ENVDeveloperHQZipCode }}</p>
            <p><strong>Country:</strong> {{ $project->ENVDeveloperHQCountry }}</p>
        </div>
        <div class="bg-white p-4 rounded border">
            <p><strong>Website:</strong> {{ $project->ENVDeveloperWebsite }}</p>
            <p><strong>Email:</strong> {{ $project->ENVDeveloperGeneralEmail }}</p>
            <p><strong>Phone:</strong> {{ $project->ENVDeveloperHQPhone }}</p>
        </div>
    </div>
</div>