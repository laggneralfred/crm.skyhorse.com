@extends('components.layouts.solar')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-center mb-6">Solar Project: {{ $project->ProjectName }}</h1>

    <div x-data="{ tab: 'general' }">
        <!-- Tabs -->
        <div class="flex flex-wrap justify-center space-x-4 border-b mb-6">
            <button @click="tab = 'general'" :class="tab === 'general' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">xxGeneral Info</button>
            <button @click="tab = 'location'" :class="tab === 'location' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Location Info</button>
            <button @click="tab = 'owner'" :class="tab === 'owner' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Owner Info</button>
            <button @click="tab = 'developer'" :class="tab === 'developer' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Developer Info</button>
            <button @click="tab = 'supplier'" :class="tab === 'supplier' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Supplier Info</button>
            <button @click="tab = 'production'" :class="tab === 'production' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Production Info</button>
            <button @click="tab = 'interconnection'" :class="tab === 'interconnection' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Interconnection Info</button>
            <button @click="tab = 'epc'" :class="tab === 'epc' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">EPC Info</button>
            <button @click="tab = 'grid_market'" :class="tab === 'grid_market' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Grid/Market Info</button>
            <button @click="tab = 'power_purchase'" :class="tab === 'power_purchase' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Power Purchase Info</button>
            <button @click="tab = 'timeline'" :class="tab === 'timeline' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Timeline Info</button>
            <button @click="tab = 'project_contacts'" :class="tab === 'project_contacts' ? 'text-green-600 font-bold border-b-2 border-green-600' : 'text-gray-600'" class="px-3 py-2">Project Contacts</button>
            <button @click="tab = 'key_company_contacts'" :class="tab === 'key_company_contacts' ? 'text-green-600 font-bold border-b-2 border-green-600' : 'text-gray-600'" class="px-3 py-2">Key Company Contacts</button>
            <button @click="tab = 'head'" :class="tab === 'head' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Head Info</button>
            <button @click="tab = 'settings_heading'" :class="tab === 'settings_heading' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Settings Heading</button>
            <button @click="tab = 'key_contacts'" :class="tab === 'key_contacts' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Key Contacts</button>
            <button @click="tab = 'other'" :class="tab === 'other' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Other Info</button>
            <button @click="tab = 'other_info'" :class="tab === 'other_info' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Other Info (Alt)</button>
        </div>

        <!-- Tab Contents -->
        <div x-show="tab === 'general'" x-cloak>@include('partials.tabs.general')</div>
        <div x-show="tab === 'location'" x-cloak>@include('partials.tabs.location')</div>
        <div x-show="tab === 'owner'" x-cloak>@include('partials.tabs.owner')</div>
        <div x-show="tab === 'developer'" x-cloak>@include('partials.tabs.developer')</div>
        <div x-show="tab === 'supplier'" x-cloak>@include('partials.tabs.supplier')</div>
        <div x-show="tab === 'production'" x-cloak>@include('partials.tabs.production')</div>
        <div x-show="tab === 'interconnection'" x-cloak>@include('partials.tabs.interconnection')</div>
        <div x-show="tab === 'epc'" x-cloak>@include('partials.tabs.epc')</div>
        <div x-show="tab === 'grid_market'" x-cloak>@include('partials.tabs.grid-market-info')</div>
        <div x-show="tab === 'power_purchase'" x-cloak>@include('partials.tabs.power-purchase-info')</div>
        <div x-show="tab === 'timeline'" x-cloak>@include('partials.tabs.timeline-info')</div>
        <div x-show="tab === 'project_contacts'" x-cloak>@include('partials.tabs.project_contacts')</div>
        <div x-show="tab === 'key_company_contacts'" x-cloak>@include('partials.tabs.key_company_contacts')</div>
        {{-- <div x-show="tab === 'settings_heading'" x-cloak>@include('partials.tabs.settings-heading')</div> --}}
        <div x-show="tab === 'key_contacts'" x-cloak>@include('partials.tabs.key_contacts')</div>
        <div x-show="tab === 'other'" x-cloak>@include('partials.tabs.other')</div>
        <div x-show="tab === 'other_info'" x-cloak>@include('partials.tabs.other-info')</div>
    </div>
</div>
@endsection