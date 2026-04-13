@props([
    'packageName',
    'tags' => [],
    'idealFor',
    'revision',
    'whatYouGet' => [],
    'inclusions' => [],
    'benefit',
    'mini' => false,
])
<div class="flex flex-col mt-4 p-4">
    <div class="flex flex-row justify-between">
        <div class="flex flex-col gap-4">
            <h1 class="{{ $mini ? 'text-[10px]' : 'text-7xl' }} font-bold clr-txt-primary">Scope of Work</h1>
            <hr class="border-2 w-1/3 border-gray-300">
        </div>

        <x-circles />
    </div>

    <div class="flex flex-1 min-h-0 rounded-2xl border border-gray-200 shadow-sm mt-4 overflow-hidden">

        {{-- Left Column --}}
        <div class="flex flex-col w-1/2 min-h-0">
            <div class="flex items-center gap-0 shrink-0">
                <div class="clr-primary flex items-center justify-center p-4 shrink-0">
                    <x-icons.proposal.bulb class="w-10 h-10 text-white" />
                </div>
                <div class="clr-bg-light flex-1 flex items-center px-5 py-4">
                    <h2 class="text-base sm:text-lg font-bold clr-txt-primary">{{ $packageName }}</h2>
                </div>
            </div>

            <div class="flex flex-col px-6 py-4 gap-3">
                <div class="flex flex-wrap gap-2 shrink-0">
                    @foreach ($tags as $tag)
                        <span
                            class="clr-primary text-white {{ $mini ? 'text-[3px] px-1 py-0.5' : 'text-md px-4 py-2' }} font-semibold rounded-md">{{ $tag }}</span>
                    @endforeach
                </div>

                <div class="flex flex-col gap-1">
                    <p class="{{ $mini ? 'text-[3px]' : 'text-md' }} font-bold clr-txt-primary">Ideal For:</p>
                    <p class="{{ $mini ? 'text-[2px]' : 'text-sm' }} clr-txt-secondary leading-relaxed">
                        {{ $idealFor }}</p>
                </div>

                <p class="{{ $mini ? 'text-[3px]' : 'text-md' }} font-bold clr-txt-primary">{{ $revision }}</p>

                <div class="flex flex-col gap-1.5">
                    <p class="{{ $mini ? 'text-[3px]' : 'text-md' }} font-bold clr-txt-primary">{{ $benefit }}
                        <br>
                    </p>
                    <ul class="flex flex-col gap-1">
                        @foreach ($whatYouGet as $item)
                            <li class="{{ $mini ? 'text-[2px]' : 'text-sm' }} clr-txt-secondary leading-snug">
                                • <span class="font-bold clr-txt-primary">{{ $item['title'] }}</span>
                                @if (!empty($item['desc']))
                                    – {{ $item['desc'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="w-px bg-gray-200 shrink-0 my-4"></div>

        {{-- Right Column --}}
        <div class="flex flex-col w-1/2 px-6 py-5 gap-3">
            <p class="{{ $mini ? 'text-[3px]' : 'text-lg' }} font-bold clr-txt-primary shrink-0">Inclusions:</p>
            <div class="flex flex-col gap-2">
                @foreach ($inclusions as $item)
                    <div class="flex items-start gap-2">
                        <span class="text-green-500 shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <p class="{{ $mini ? 'text-[2px]' : 'text-md' }} clr-txt-secondary leading-snug">
                            <span class="font-bold clr-txt-primary">{{ $item['title'] }}</span>
                            – {{ $item['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
