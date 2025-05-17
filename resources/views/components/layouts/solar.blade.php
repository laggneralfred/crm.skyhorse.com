<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solar Project Viewer</title>

    {{-- Load compiled Tailwind & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles --}}
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 antialiased">

    {{-- Content goes here --}}
    <div class="min-h-screen">
        @yield('content')
    </div>

    {{-- Alpine for tab switching --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Livewire scripts --}}
    @livewireScripts
</body>
</html>
