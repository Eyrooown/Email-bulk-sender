<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $proposal->title ?? 'Proposal Editor' }}</title>

    @if(app()->environment('production'))
        @php
            $manifestPath = public_path('build/manifest.json');
            $manifest = file_exists($manifestPath) ? (json_decode(file_get_contents($manifestPath), true) ?? []) : [];
        @endphp
        @if(!empty($manifest['resources/css/app.css']['file']))
            <link rel="stylesheet" href="{{ asset('build/'.$manifest['resources/css/app.css']['file']) }}">
        @endif
        @if(!empty($manifest['resources/js/app.js']['file']))
            <script type="module" src="{{ asset('build/'.$manifest['resources/js/app.js']['file']) }}"></script>
        @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles

    <style>
        html, body { height: 100%; overflow: hidden; }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 font-sans antialiased">
    {{ $slot }}

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('saved', () => {
                const el = document.getElementById('toast');
                if (!el) return;
                el.classList.remove('opacity-0', 'translate-y-2');
                el.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    el.classList.add('opacity-0', 'translate-y-2');
                    el.classList.remove('opacity-100', 'translate-y-0');
                }, 1800);
            });
        });
    </script>
</body>
</html>
