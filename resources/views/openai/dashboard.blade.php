<x-app-layout>
    <div class="border-b border-gray-300"></div>

    <div class="py-10 px-6 mx-auto max-w-7xl space-y-8">
        <h2 class="text-2xl font-semibold text-gray-800">Solar Projects Query Tool</h2>

        @if($pastQueries->isNotEmpty())
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Saved Queries</h2>

                <div class="overflow-x-auto border border-gray-300 rounded shadow bg-white">
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
                                    <form method="POST" action="{{ route('query.run', $query) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:underline text-left w-full">
                                            {{ $query->question }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-sm">
                                    {{ $query->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-2 text-right space-x-2 whitespace-nowrap">
                                    <form method="POST" action="{{ route('query.favorite', $query) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-yellow-500 hover:text-yellow-700" title="Toggle Favorite">
                                            {{ $query->favorited ? '⭐' : '☆' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('query.delete', $query) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                            ❌
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('query.clear') }}" class="mt-4 text-right" onsubmit="return confirm('Delete all saved queries?');">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:underline">
                        🧹 Delete All
                    </button>
                </form>
            </div>
        @endif

        <form method="POST" action="{{ route('query.generate') }}">
            @csrf
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <input name="prompt" type="text"
                       class="w-full border border-gray-300 rounded p-3 shadow-sm bg-white text-black"
                       placeholder="Ask a question..." value="{{ old('prompt', $prompt ?? '') }}">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">Submit</button>
                    @if(!empty($result))
                        <button name="export" value="1" class="bg-green-600 text-white px-4 py-2 rounded shadow">Export CSV</button>
                    @endif
                </div>
            </div>
        </form>

        @if(!empty($sql))
            <div class="text-sm bg-gray-100 text-gray-700 border border-gray-300 rounded p-4">
                <strong>Generated SQL:</strong>
                <div class="mt-1 font-mono text-xs text-black">{{ $sql }}</div>
            </div>
        @endif

        @if(!empty($result))
            <div class="overflow-x-auto border border-gray-300 rounded bg-white shadow">
                <table class="min-w-full text-sm text-left table-auto">
                    <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        @foreach (array_keys((array) $result[0]) as $column)
                            <th class="px-4 py-2 border">{{ $column }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($result as $row)
                        <tr class="even:bg-gray-50">
                            @foreach ((array) $row as $cell)
                                <td class="px-4 py-2 border text-right text-black">
                                    {{ is_numeric($cell) ? number_format($cell) : $cell }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(!empty($textResponse))
            <div class="bg-yellow-100 text-yellow-800 p-4 border border-yellow-300 rounded">
                <strong>AI Answer:</strong> {{ $textResponse }}
            </div>
        @endif
    </div>
</x-app-layout>
