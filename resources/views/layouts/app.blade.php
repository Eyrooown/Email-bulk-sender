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

            <a href="/dashboard"
               class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap hover-clr-accent {{ request()->is('dashboard') ? 'focus-clr-accent' : 'text-white' }}">
                <x-icons.home classes="w-6 h-6" />
                <span class="hidden group-hover:block">Home</span>
            </a>

            <a href="/compose"
               class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('compose') ? 'focus-clr-accent' : '' }} hover-clr-accent">
                <x-icons.email classes="w-6 h-6" />
                <span class="hidden group-hover:block">Compose Email</span>
            </a>

            <a href="{{ route('draft') }}"
               class="flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap {{ request()->is('draft') ? 'focus-clr-accent' : '' }} hover-clr-accent">
                <x-icons.icon-draft classes="w-6 h-6" />
                <span class="hidden group-hover:block">Draft</span>
            </a>

        </nav>

        <div class="p-2 border-t border-white/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-4 px-3 py-3 rounded-lg whitespace-nowrap hover-clr-accent">
                    <img/> <span class="hidden group-hover:block">Logout</span>
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

@livewireScripts
</body>
</html>
