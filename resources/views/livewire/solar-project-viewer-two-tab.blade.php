@extends('components.layouts.solar')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-center mb-6">Solar Project: {{ $project->ProjectName }}</h1>

    <div x-data="{ tab: 'two-tab-a' }">
        <!-- Tabs -->
        <div class="flex flex-wrap justify-center space-x-4 border-b mb-6">
            <button @click="tab = 'two-tab-a'" :class="tab === 'two-tab-a' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Overview</button>
            <button @click="tab = 'two-tab-b'" :class="tab === 'two-tab-b' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Details</button>
            <button @click="tab = 'two-tab-c'" :class="tab === 'two-tab-c' ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'text-gray-600'" class="px-3 py-2">Technical</button>
        </div>

        <!-- Tab Contents -->
        <div x-show="tab === 'two-tab-a'" x-cloak>
            @include('partials.tabs.two-tab-a')
        </div>
        <div x-show="tab === 'two-tab-b'" x-cloak>
            @include('partials.tabs.two-tab-b')
        </div>
        <div x-show="tab === 'two-tab-c'" x-cloak>
            @include('partials.tabs.two-tab-c')
        </div>
    </div>
</div>
@endsection
<!-- Blade content placeholder -->