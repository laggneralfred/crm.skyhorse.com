
<div class="text-center my-6">
    <h1 class="text-2xl font-bold">Solar Project: {{ $project->ProjectName }}</h1>
</div>

<div class="flex justify-center space-x-4 border-b pb-2 mb-6">
    @foreach($tabs as $tab)
        <button 
            wire:click="$set('activeTab', '{{ $tab['key'] }}')" 
            class="px-4 py-2 font-semibold focus:outline-none transition-colors duration-200 
                   {{ $activeTab === $tab['key'] ? ($tab['key'] === 'projectContacts' ? 'text-green-600 border-b-2 border-green-600' : 'text-blue-600 border-b-2 border-blue-600') : 'text-gray-600' }}">
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>

<div>
    @includeIf('partials.tabs.' . $activeTab)
</div>
