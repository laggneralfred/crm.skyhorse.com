
@extends('components.layouts.solar')

@section('content')
<div class="max-w-7xl mx-auto p-6 text-gray-800">

    <!-- Project Header -->
    <h1 class="text-2xl font-bold text-center mb-6">
        Solar Project: {{ $project->ProjectName }}
    </h1>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'general' }">
        <div class="flex border-b mb-6 space-x-4 justify-center">
            <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-b-2 border-blue-600 text-blue-600' : ''" class="pb-2">General Info</button>
            <button @click="activeTab = 'supplier'" :class="activeTab === 'supplier' ? 'border-b-2 border-blue-600 text-blue-600' : ''" class="pb-2">Supplier Info</button>
        </div>

        <!-- General Info Tab -->
        <div x-show="activeTab === 'general'" x-cloak class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">General Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border p-4 rounded bg-gray-50 space-y-2">
                    <p><strong>Project Type:</strong> {{ $project->ProjectType }}</p>
                    <p><strong>Project Capacity:</strong> {{ $project->ProjectCapacityMW }} MW</p>
                    <p><strong>Developer:</strong> {{ $project->Developer }}</p>
                    <p><strong>First Power Date:</strong> {{ $project->FirstPowerDate }}</p>
                </div>
                <div class="border p-4 rounded bg-gray-50 space-y-2">
                    <p><strong>Project Status:</strong> {{ $project->ProjectStatus }}</p>
                    <p><strong>Offtaker:</strong> {{ $project->ProjectOfftaker }}</p>
                    <p><strong>Owner:</strong> {{ $project->Owner }}</p>
                </div>
            </div>
        </div>

        <!-- Supplier Info Tab -->
        <div x-show="activeTab === 'supplier'" x-cloak class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">Supplier Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border p-4 rounded bg-gray-50 space-y-2">
                    <p><strong>Supplier:</strong> {{ $project->Supplier }}</p>
                    <p><strong>Website:</strong> {{ $project->SupplierWebsite }}</p>
                </div>
                <div class="border p-4 rounded bg-gray-50 space-y-2">
                    <p><strong>Unit Supplied:</strong> {{ $project->UnitSupplied }}</p>
                    <p><strong>Phone:</strong> {{ $project->SupplierHQPhone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
