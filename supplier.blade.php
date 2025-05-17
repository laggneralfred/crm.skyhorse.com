<div>
    <h2 class="text-xl font-bold mb-4">Supplier Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded border">
            <p><strong>Supplier:</strong> {{ $project->Supplier }}</p>
            <p><strong>Unit Supplied:</strong> {{ $project->UnitSupplied }}</p>
        </div>
        <div class="bg-white p-4 rounded border">
            <p><strong>Website:</strong> {{ $project->SupplierWebsite }}</p>
            <p><strong>Phone:</strong> {{ $project->SupplierHQPhone }}</p>
        </div>
    </div>
</div>