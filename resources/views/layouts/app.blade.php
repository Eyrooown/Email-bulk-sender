<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" style="color-scheme: light;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
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

            <a href="{{ route('proposal') }}"
            class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('proposal*') ? 'focus-clr-accent' : 'text-white' }} hover-clr-accent">
            <x-icons.proposal classes="w-6 h-6" />
            <span class="hidden group-hover:block">Proposal</span>
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
            <button type="button" onclick="document.getElementById('logout-modal').showModal()"
                class="w-full flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap hover-clr-accent">
                <x-icons.logout classes="w-6 h-6" />
                <span class="hidden group-hover:block">Log out</span>
            </button>

            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
        </div>

<dialog id="logout-modal" class="modal">
    <div class="modal-box max-w-sm">
        <h3 class="clr-text-primary font-bold text-lg mb-2">Confirm Logout</h3>
        <p class="clr-text-primary text-sm">Are you sure you want to log out?</p>
        <div class="modal-action gap-4">
            <button onclick="document.getElementById('logout-modal').close()" class="btn clr-bg-accent text-white p-4">Cancel</button>
            <button onclick="document.getElementById('logout-form').submit()" class="btn clr-bg-accent text-white p-4">Log out</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button></button>
    </form>
</dialog>

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
