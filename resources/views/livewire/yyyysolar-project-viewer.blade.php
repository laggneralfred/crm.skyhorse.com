@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-center mb-6">Solar Project: {{ $project->ProjectName }}</h1>

    <div x-data="{ tab: 'general' }">
        <div class="flex justify-center space-x-4 border-b mb-6">
            <button @click="tab = 'general'" :class="{ 'text-blue-600 font-bold': tab === 'general' }">xxxGeneral Info</button>
            <button @click="tab = 'location'" :class="{ 'text-blue-600 font-bold': tab === 'location' }">Location Info</button>
            <button @click="tab = 'project_contacts'" :class="{ 'text-green-600 font-bold': tab === 'project_contacts' }">Project Contacts</button>
            <button @click="tab = 'key_company_contacts'" :class="{ 'text-green-600 font-bold': tab === 'key_company_contacts' }">Key Company Contacts</button>
        </div>

        <div x-show="tab === 'general'">
            @include('partials.tabs.general')
        </div>
        <div x-show="tab === 'location'">
            @include('partials.tabs.location')
        </div>
        <div x-show="tab === 'project_contacts'">
            @include('partials.tabs.project_contacts')
        </div>
        <div x-show="tab === 'key_company_contacts'">
            @include('partials.tabs.key_company_contacts')
        </div>
    </div>

    <div class="text-right mb-4">
        <button
            onclick="handleClose()"
            class="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm"
        >
            ✕ Close
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleClose() {
    // Try to close the window
    window.close();

    // If that fails (e.g. inside iframe), fallback to HubSpot or redirect
    setTimeout(() => {
        if (!window.closed) {
            window.location.href = 'https://app.hubspot.com/contacts';
        }
    }, 500);
}
</script>
@endpush
