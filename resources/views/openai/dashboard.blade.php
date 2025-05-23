
<x-layouts.app.sidebar title="Solar Projects Query Tool">

    <div class="py-10 px-6 mx-auto max-w-7xl space-y-8 bg-white dark:bg-[#0f172a] text-gray-900 dark:text-gray-100">
    {{-- Search form --}}
        <form method="POST" action="{{ route('query.dashboard.generate') }}">
            @csrf
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                <input name="prompt" type="text" class="w-full border border-gray-300 rounded p-3 shadow-sm bg-white text-black"
                       placeholder="Ask a question..." value="{{ old('prompt', $prompt ?? '') }}">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded shadow">Submit</button>
                    @if(!empty($result))
                        <button name="export" value="1" class="bg-green-600 text-white px-4 py-2 rounded shadow">Export CSV</button>
                    @endif
                </div>
            </div>
        </form>

        {{-- SQL Preview --}}
        @if(!empty($sql))
            <div class="text-sm bg-gray-100 text-gray-700 border border-gray-300 rounded p-4">
                <strong>Generated SQL:</strong>
                <div class="mt-1 font-mono text-xs text-black">{{ $sql }}</div>
            </div>
        @endif

        {{-- Results Table --}}
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
</x-layouts.app.sidebar>
