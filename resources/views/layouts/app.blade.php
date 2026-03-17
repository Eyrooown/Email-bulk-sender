<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,400i,500,600,700,700i&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Scripts -->
        @php
            $useBuildOnNetwork = !app()->environment('production') && !in_array(request()->getHost(), ['localhost', '127.0.0.1'], true);
            $manifestPath = public_path('build/manifest.json');
        @endphp
        @if($useBuildOnNetwork && file_exists($manifestPath))
            @php
                $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
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
</head>
<body class="font-sans antialiased">

<div class="flex h-screen relative">

    <div class="group absolute left-0 top-0 h-full text-white flex flex-col transition-all duration-300 ease-in-out w-16 hover:w-64 overflow-hidden z-50"
         style="background-color: #102B3C;">

        <div class="flex items-center h-16 px-3 border-b border-white/20">
            <img src="{{ asset('images/icon-white.png') }}" alt="Icon"
                 class="w-8 block group-hover:hidden" />
            <img src="{{ asset('images/odecci-plain-logo.png') }}" alt="Logo"
                 class="w-36 hidden group-hover:block" />
        </div>

        <nav class="flex flex-col p-2 gap-1 mt-4 flex-1">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap hover-clr-accent {{ request()->is('inbox') ? 'focus-clr-accent' : 'text-white' }}">
                <x-icons.inbox classes="w-6 h-6" />
                <span class="hidden group-hover:block">Inbox</span>
            </a>

            <a href="/compose"
               class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('compose') ? 'focus-clr-accent' : '' }} hover-clr-accent">
                <x-icons.email classes="w-6 h-6" />
                <span class="hidden group-hover:block">Compose Email</span>
            </a>

            <a href="{{ route('archive') }}"
            class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('archive') ? 'focus-clr-accent' : 'text-white' }} hover-clr-accent">
            <x-icons.archive classes="w-6 h-6" />
            <span class="hidden group-hover:block">Archive</span>
            </a>

            @if(Auth::user()?->is_admin)
            <a href="{{ route('accounts') }}"
            class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('accounts') ? 'focus-clr-accent' : '' }} hover-clr-accent">
                <x-icons.account classes="w-6 h-6" />
                <span class="hidden group-hover:block">Accounts</span>
            </a>
            @endif
        </nav>

        <div class="p-2 border-t border-white/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap hover-clr-accent">
                    <x-icons.logout classes="w-6 h-6" />
                    <span class="hidden group-hover:block">Log out</span>
                </button>
            </form>
        </div>

    </div>

    <div class="flex-1 flex flex-col overflow-hidden bg-gray-100 ml-16">

        <div class="clr-primary shadow px-6 py-4 h-16 flex items-center justify-between">
            <h1 class="text-xl font-semibold">{{ $header ?? '' }}</h1>
            <div class="flex items-center gap-3">
                <span class="text-base-100">Hi, <strong>{{ Auth::user()->name ?? 'User' }}</strong>!</span>
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">👤</div>
            </div>
        </div>

        <main class="flex-1 overflow-auto p-6">
            {{ $slot }}
        </main>

    </div>

</div>

<livewire:sending-progress-toast />

@livewireScripts
</body>
</html>
