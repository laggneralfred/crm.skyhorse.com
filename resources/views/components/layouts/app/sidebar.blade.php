@props(['title' => ''])

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ? $title . ' | Skyhorse AI' : 'Skyhorse AI' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">

<div class="lg:flex lg:h-screen overflow-hidden">

    {{-- Sidebar (desktop) --}}
    <aside class="hidden lg:block lg:w-64 bg-white border-r border-gray-200">
        <div class="p-5 text-xl font-bold border-b border-gray-300 text-gray-800 break-words leading-snug">
            {{ $title ?? 'Skyhorse' }}
        </div>

        <nav class="px-4 py-6 space-y-2 text-sm">
            <a href="{{ route('query.dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100
               {{ request()->routeIs('query.dashboard') ? 'bg-indigo-200 font-semibold' : '' }}">
                🔍 Solar Projects Query
            </a>
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-indigo-100
               {{ request()->routeIs('dashboard') ? 'bg-indigo-200 font-semibold' : '' }}">
                🧭 Legacy Dashboard
            </a>
        </nav>
    </aside>

    {{-- Mobile sidebar and topbar --}}
    <div x-data="{ sidebarOpen: false }" class="lg:hidden relative z-50" x-cloak>
        <div x-show="sidebarOpen" x-transition.opacity
             class="fixed inset-0 bg-black bg-opacity-50"
             @click="sidebarOpen = false"></div>

        <aside x-show="sidebarOpen"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-150"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50">
            <div class="p-5 text-xl font-bold border-b border-gray-300 text-gray-800 break-words leading-snug">
                {{ $title ?? 'Skyhorse' }}
            </div>

            <nav class="px-4 py-6 space-y-2 text-sm">
                <a href="{{ route('query.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-indigo-100
                   {{ request()->routeIs('query.dashboard') ? 'bg-indigo-200 font-semibold' : '' }}">
                    🔍 Solar Projects Query
                </a>
                <a href="{{ route('dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-indigo-100
                   {{ request()->routeIs('dashboard') ? 'bg-indigo-200 font-semibold' : '' }}">
                    🧭 Legacy Dashboard
                </a>
            </nav>
        </aside>

        <header class="flex items-center justify-between p-4 border-b border-gray-200 bg-white">
            <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="text-sm font-semibold text-gray-700">{{ $title }}</div>
        </header>
    </div>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 p-6 overflow-y-auto bg-white">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
