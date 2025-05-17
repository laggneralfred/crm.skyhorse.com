<div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white w-full max-w-6xl max-h-[90vh] overflow-y-auto rounded-lg shadow-xl p-6">
        <!-- Close Button -->
        <button
            class="absolute top-4 right-4 text-gray-500 hover:text-red-500 text-2xl font-bold"
            onclick="document.getElementById('popup').style.display='none'"
        >
            ×
        </button>

        <!-- Header -->
        <h2 class="text-2xl font-semibold text-center mb-6">
            Solar Project: {{ $project->ProjectName }}
        </h2>

        <!-- Tabs and Content -->
        @include('livewire.solar-project-viewer-full-tabs', ['project' => $project])
    </div>
</div>
