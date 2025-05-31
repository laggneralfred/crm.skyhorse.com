<x-app-layout>
    <div class="border-b border-gray-300"></div>

    <div class="py-10 px-6 mx-auto max-w-7xl space-y-8" x-data="dashboardTabs">

        <h2 class="text-2xl font-semibold text-gray-800">Solar Projects Query Tool</h2>


        {{-- Query Form --}}
        <form method="POST" action="{{ route('query.generate') }}">
            @csrf
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4 mt-4">
                <input name="prompt" type="text"
                       class="w-full border border-gray-300 rounded p-3 shadow-sm bg-white text-black"
                       placeholder="Ask a question..." value="{{ old('prompt', $prompt ?? '') }}">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">Submit</button>
                    @if(!empty($tableData) && count($tableData))
                        <button name="export" value="1" class="bg-green-600 text-white px-4 py-2 rounded shadow">Export CSV</button>
                    @endif
                </div>
            </div>
        </form>

        {{-- Saved Queries --}}
        {{-- Saved Queries (Collapsible) --}}
        <div x-data="{ openQueries: false }" class="mt-8 border border-gray-300 rounded shadow bg-white">
            <button
                    @click="openQueries = !openQueries"
                    class="w-full text-left px-4 py-3 bg-gray-100 hover:bg-gray-200 text-lg font-semibold text-gray-800 flex justify-between items-center"
            >
                Saved Queries
                <svg x-show="!openQueries" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="openQueries" x-cloak class="w-5 h-5 transform rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openQueries" x-collapse>
                @if($pastQueries->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left table-auto">
                            <thead class="bg-gray-100 text-gray-800">
                            <tr>
                                <th class="px-4 py-2">Query</th>
                                <th class="px-4 py-2">Created</th>
                                <th class="px-4 py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($pastQueries as $query)
                                <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('query.run', $query) }}">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:underline text-left w-full">
                                                {{ $query->question }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-gray-600 text-sm">
                                        {{ $query->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <form method="POST" action="{{ route('query.favorite', $query) }}">
                                            @csrf
                                            <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Toggle Favorite">
                                                {{ $query->favorited ? '⭐' : '☆' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('query.delete', $query) }}">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">❌</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <form method="POST" action="{{ route('query.clear') }}" class="mt-2 px-4 py-2 text-right" onsubmit="return confirm('Delete all saved queries?');">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline">
                            🧹 Delete All
                        </button>
                    </form>
                @else
                    <div class="px-4 py-4 text-gray-600 text-sm">No saved queries yet.</div>
                @endif
            </div>
        </div>


        {{-- Tabs UI --}}
        @if(!empty($tableData) && count($tableData))
            <div class="mt-10">
                <div class="mb-4 flex border-b border-gray-300 text-sm font-medium space-x-4">
                    <button @click="switchTab('table')"
                            :class="tab === 'table' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="pb-2 px-3 hover:text-blue-600">📄 Table</button>

                    <button @click="switchTab('map')"
                            :class="tab === 'map' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="pb-2 px-3 hover:text-blue-600">🗺️ Map</button>

                    <button @click="switchTab('sql')"
                            :class="tab === 'sql' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="pb-2 px-3 hover:text-blue-600">💾 SQL</button>
                </div>

{{--                <pre class="text-xs bg-gray-100 border rounded p-2 overflow-auto">{{ print_r($mapData, true) }}</pre>--}}

                {{-- Table Tab --}}
                <div x-show="tab === 'table'" x-cloak>
                    <div class="overflow-x-auto border border-gray-300 rounded bg-white shadow">
                        <table class="min-w-full text-sm text-left table-auto">
                            @php
                                $hiddenColumns = ['latitude', 'longitude'];
                            @endphp
                            <thead class="bg-gray-100 text-gray-800">
                            <tr>
                                @foreach (array_keys($tableData[0]) as $column)
                                    @if (!in_array(strtolower($column), $hiddenColumns))
                                        <th class="px-4 py-2 border">{{ $column }}</th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tableData as $row)
                                <tr class="even:bg-gray-50">
                                    @foreach ($row as $key => $value)
                                        @if (!in_array(strtolower($key), $hiddenColumns))
                                            <td class="px-4 py-2 border text-right text-black">
                                                {{ is_numeric($value) ? number_format($value, 2) : $value }}
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                {{-- Map Tab --}}
                <div x-show="tab === 'map'" x-cloak>
                    <div id="project-map" class="h-[500px] w-full rounded border mt-4"></div>
                </div>

                {{-- SQL Tab --}}
                <div x-show="tab === 'sql'" x-cloak>
                    <div class="text-sm bg-gray-100 text-gray-700 border border-gray-300 rounded p-4 font-mono whitespace-pre-wrap">
                        <strong>Generated SQL:</strong>
                        <div class="mt-2 text-black text-xs">{{ $sql }}</div>
                    </div>
                </div>
            </div>
        @elseif(!empty($textResponse))
            <div class="bg-yellow-100 text-yellow-800 p-4 border border-yellow-300 rounded">
                <strong>AI Answer:</strong> {{ $textResponse }}
            </div>
        @endif
    </div>

    {{-- Leaflet assets --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Injected map data from controller
        const mapData = @json($mapData);

        // Load Leaflet map when triggered
        function loadMap() {

            console.log("Project keys:", Object.keys(mapData[0]));


            const container = document.getElementById('project-map');
            if (!window.mapInitialized && mapData.length > 0 && container) {
                // Fix: force height in case Alpine makes div visible too late
                container.style.height = '500px';

                const map = L.map(container).setView([mapData[0].latitude, mapData[0].longitude], 6);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                mapData.forEach(project => {
                    const lat = parseFloat(project.latitude);
                    const lng = parseFloat(project.longitude);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        const name = project["Project name"] || "Unnamed project";
                        const mwh = project["Total MWh Generated"]
                            ? Number(project["Total MWh Generated"]).toLocaleString()
                            : "N/A";

                        const popup = `
            <strong>${name}</strong><br>
            MWh: ${mwh}
        `;

                        const marker = L.marker([lat, lng]).addTo(map);
                        marker.bindPopup(popup);

                        // Optional: popup on hover
                        marker.on('mouseover', function () {
                            this.openPopup();
                        });

                        marker.on('mouseout', function () {
                            this.closePopup();
                        });
                    } else {
                        console.warn("Invalid lat/lng for project:", project);
                    }
                });


                window.mapInitialized = true;
            } else {
                console.warn("Map not loaded — already initialized or container not ready.");
            }
        }


        // Alpine.js component for dashboard tab handling
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardTabs', () => ({
                tab: 'table',
                mapLoaded: false,
                switchTab(newTab) {
                    this.tab = newTab;
                    if (newTab === 'map' && !this.mapLoaded) {
                        this.mapLoaded = true;
                        setTimeout(loadMap, 200); // Slight delay to ensure tab is rendered
                    }
                }
            }));
        });
    </script>


</x-app-layout>
