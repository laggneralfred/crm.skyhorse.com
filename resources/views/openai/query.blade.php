<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Generator and AI Assistant</title>
</head>
<body>
    <h1>Ask About Solar Projects</h1>

    <form method="POST" action="{{ route('openai.generate') }}">
        @csrf
        <textarea name="prompt" rows="4" cols="70" placeholder="Ask a question...">{{ old('prompt') }}</textarea>
        <br><br>
        <button type="submit">Submit</button>
    </form>

    @if (isset($sql) && $sql)
        <h2>Generated SQL:</h2>
        <pre>{{ $sql }}</pre>
    @endif

    @if (isset($result) && is_array($result))
        <h2>Query Result:</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    @foreach (array_keys((array) $result[0]) as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        @foreach ((array) $row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if (isset($textResponse) && $textResponse)
        <h2>AI Answer:</h2>
        <p>{{ $textResponse }}</p>
    @endif
</body>
</html>
