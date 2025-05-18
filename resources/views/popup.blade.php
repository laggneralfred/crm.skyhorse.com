<!-- resources/views/popup.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solar Project Popup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vite / Alpine -->
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}">
    <script type="module" src="{{ Vite::asset('resources/js/app.js') }}"></script>

    <style>
        /* Force visibility and stickiness for the close button */
        #close-btn {
            position: sticky;
            bottom: 0;
            z-index: 10;
            background: white;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Modal Overlay -->
<div
        x-data="{ show: true }"
        x-show="show"
        class="fixed inset-0 z-50 bg-black bg-opacity-60 flex items-start justify-center p-4 overflow-auto"
>
    <!-- Popup Content -->
    <div class="bg-white w-full max-w-6xl mt-10 rounded-lg shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-2xl font-bold">Solar Project: {{ $project->ProjectName }}</h2>
            <button @click="show = false" class="text-2xl text-gray-600 hover:text-black">&times;</button>
        </div>

        <div class="px-6 py-4">
            @include('partials.popup-content', ['project' => $project])
        </div>

        <!-- Always-visible Close Button -->
        <div id="close-btn" class="text-center mt-10">
            <button
                    onclick="tryClose()"
                    class="px-4 py-2 bg-green-600 text-black rounded hover:bg-green-700 transition"
            >
                Close Window
            </button>
        </div>
    </div>
</div>
<script>
    function tryClose() {
        window.close();

        // If still open after 500ms, fallback to redirect
        setTimeout(() => {
            if (!window.closed) {
                window.location.href = '/dashboard';
            }
        }, 500);
    }
</script>

</body>
</html>
