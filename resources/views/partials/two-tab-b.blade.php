
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">Developer & Owner Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Developer:</strong> {{ $project->Developer }}</p>
            <p><strong>Developer Website:</strong> {{ $project->ENVDeveloperWebsite }}</p>
            <p><strong>HQ Address:</strong> {{ $project->ENVDeveloperHQAddress }}</p>
            <p><strong>HQ Phone:</strong> {{ $project->ENVDeveloperHQPhone }}</p>
        </div>
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Owner:</strong> {{ $project->Owner }}</p>
            <p><strong>Former Owner:</strong> {{ $project->FormerOwner }}</p>
            <p><strong>Percent Owned:</strong> {{ $project->PercentOwned }}</p>
        </div>
    </div>

    <h2 class="text-xl font-bold my-6">Supplier Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Supplier:</strong> {{ $project->Supplier }}</p>
            <p><strong>Website:</strong> {{ $project->SupplierWebsite }}</p>
            <p><strong>Email:</strong> {{ $project->SupplierGeneralEmail }}</p>
        </div>
        <div class="p-4 border rounded bg-gray-50">
            <p><strong>Phone:</strong> {{ $project->SupplierHQPhone }}</p>
            <p><strong>HQ Address:</strong> {{ $project->SupplierHQAddress }}</p>
            <p><strong>City:</strong> {{ $project->SupplierHQCity }}</p>
        </div>
    </div>
</div>
