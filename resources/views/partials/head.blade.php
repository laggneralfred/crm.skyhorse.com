<!-- resources/views/partials/head.blade.php -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $title ?? 'Solar Project Viewer' }}</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
