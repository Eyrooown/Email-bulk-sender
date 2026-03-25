@php
    $c = isset($contentOverride) ? $contentOverride : ($slide->content ?? []);
    $layout = $slide->layout ?? 'blank';
    $h1 = $mini ? 'text-[4px] leading-tight' : 'text-4xl md:text-5xl leading-tight';
    $h2 = $mini ? 'text-[3px] leading-tight' : 'text-2xl md:text-3xl leading-tight';
    $sub = $mini ? 'text-[2.5px] leading-normal' : 'text-lg leading-relaxed';
    $body = $mini ? 'text-[2px] leading-normal' : 'text-base leading-relaxed';
    $qot = $mini ? 'text-[3.5px] leading-tight italic' : 'text-2xl md:text-3xl italic leading-snug';
    $auth = $mini ? 'text-[2px]' : 'text-sm';
    $pad = $mini ? 'p-1' : 'p-10 md:p-16';

    // Fixed template sizing helpers (used by proposal-slide-test pages turned into layouts)
    $fxPadX = $mini ? 'px-2' : 'px-12';
    $fxPadY = $mini ? 'py-2' : 'py-6';
    $fxGap = $mini ? 'gap-2' : 'gap-8';
    $fxBarW = $mini ? 'w-6' : 'w-32';
    $fxH1 = $mini ? 'text-[10px] leading-none' : 'text-7xl';
    $fxH2 = $mini ? 'text-[8px] leading-none' : 'text-6xl';
    $fxTitle = $mini ? 'text-[5px] leading-tight' : 'text-xl';
    $fxBody = $mini ? 'text-[3px] leading-tight' : 'text-sm';
    $fxCardPad = $mini ? 'p-2' : 'p-8';
    $fxIcon = $mini ? 'w-6 h-6' : 'w-16 h-16';
@endphp

@switch($layout)
    @case('fixed-cover')
        <div class="absolute inset-0 bg-white overflow-hidden">
            <div class="flex w-full h-full">
                <div class="{{ $fxBarW }} clr-primary shrink-0"></div>
                <div class="flex flex-col w-full h-full {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-row items-center {{ $mini ? 'gap-1' : 'gap-4' }}">
                            <x-logo />
                            <div class="w-px {{ $mini ? 'h-6' : 'h-12' }} clr-primary shrink-0"></div>
                            <p class="{{ $mini ? 'text-[4px]' : 'text-lg' }} clr-txt-secondary">
                                {{ $c['tagline'] ?? 'Your Partner Towards Digital Innovation' }}
                            </p>
                        </div>
                        <x-circles />
                    </div>

                    <div class="flex flex-row flex-1 {{ $mini ? 'gap-2' : 'gap-8' }}">
                        <div class="w-px clr-primary shrink-0"></div>
                        <div class="flex flex-col justify-center {{ $mini ? 'gap-1' : 'gap-2' }}">
                            <p class="{{ $fxH1 }} font-bold clr-txt-primary">{{ $c['line1'] ?? 'WEBSITE' }}</p>
                            <p class="{{ $fxH1 }} font-normal clr-txt-primary">{{ $c['line2'] ?? 'DEVELOPMENT' }}</p>
                            <p class="{{ $fxH1 }} font-extralight clr-txt-secondary">{{ $c['line3'] ?? 'PROPOSAL' }}</p>
                        </div>
                        <div class="flex flex-1 justify-center items-center">
                            <img src="{{ asset('images/icon-dark.png') }}" alt="Icon"
                                class="{{ $mini ? 'h-10' : 'h-3/4' }} w-auto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    @case('fixed-executive')
        <div class="absolute inset-0 bg-white overflow-hidden">
            <div class="flex w-full h-full">
                <div class="flex flex-col w-full h-full {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                    <div class="flex justify-between items-center">
                        <p class="{{ $fxH1 }} clr-txt-primary">
                            {{ $c['heading'] ?? 'Executive Summary' }}
                        </p>
                        <x-circles />
                    </div>
                    <hr class="border-clr-primary {{ $mini ? 'w-1/3 border' : 'w-2/5 border-2' }}">

                    <div class="flex flex-row w-full flex-1 justify-center items-center {{ $mini ? 'gap-1' : 'gap-2' }} overflow-hidden">
                        <div class="relative {{ $mini ? 'w-[70%]' : 'w-4/5' }} bg-white shadow-xl rounded-xl {{ $mini ? 'p-2' : 'p-4' }} z-10">
                            <div class="{{ $mini ? 'text-[3px]' : 'text-base' }} clr-txt-primary leading-relaxed space-y-2">
                                @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                    @if (trim($line) !== '')
                                        <p>{{ trim($line) }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-col relative {{ $mini ? '-ml-6' : '-ml-44' }} justify-end">
                            <img src="{{ asset('images/executive-bg.png') }}" alt="BG"
                                class="{{ $mini ? 'h-16' : 'h-3/4' }} w-auto" />
                            <div class="absolute rounded-full {{ $mini ? 'h-20 w-20 -bottom-8 -right-8' : 'h-96 w-96 -bottom-40 -right-40' }} clr-primary z-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    @case('fixed-whois')
        <div class="absolute inset-0 bg-white overflow-hidden">
            <div class="flex w-full h-full">
                <div class="{{ $fxBarW }} clr-primary shrink-0"></div>
                <div class="flex flex-col w-full h-full {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                    <div class="flex justify-between items-center">
                        <x-logo />
                        <div class="flex flex-col">
                            <div class="flex flex-row justify-center items-center">
                                <h1 class="{{ $fxH2 }} clr-txt-secondary {{ $mini ? 'mt-1' : 'mt-6' }}">
                                    {{ $c['top_heading'] ?? 'OUR STRATEGY' }}
                                </h1>
                                <x-circles />
                            </div>
                            <hr class="{{ $mini ? 'w-1/2 mt-2 border' : 'w-3/4 mt-10 border-2' }} border-clr-primary">
                        </div>
                    </div>

                    <div class="flex flex-row justify-between w-full flex-1 min-w-0">
                        <div class="flex flex-col flex-1 min-w-0">
                            <h1 class="{{ $fxH1 }} font-medium clr-txt-primary">
                                {!! nl2br(e($c['heading'] ?? "Who is\nOdecci?")) !!}
                            </h1>
                            <div class="{{ $mini ? 'mt-2' : 'mt-10' }} {{ $mini ? 'text-[3px]' : 'text-base' }} clr-txt-primary space-y-2">
                                @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                    @if (trim($line) !== '')
                                        <p>{{ trim($line) }}</p>
                                    @endif
                                @endforeach
                                @if (!empty($c['website']))
                                    <p class="{{ $mini ? 'mt-1' : 'mt-4' }}">
                                        Visit our website:
                                        <span class="underline decoration-solid">{{ $c['website'] }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-1 flex-row justify-center items-center min-w-0">
                            <div class="grid grid-cols-2 grid-rows-3 {{ $mini ? 'gap-2' : 'gap-20' }}">
                                @foreach (($c['bullets'] ?? []) as $bullet)
                                    <div class="flex flex-row justify-center items-center {{ $mini ? 'gap-1' : 'gap-4' }}">
                                        <div class="flex justify-center items-center {{ $mini ? 'h-6 w-6' : 'h-20 w-20' }} shrink-0 rounded-full clr-bg-secondary text-base-100">
                                            <x-icons.diamond class="{{ $mini ? 'w-2 h-2' : 'w-6 h-6' }}" />
                                        </div>
                                        <p class="clr-txt-secondary font-bold {{ $mini ? 'text-[4px]' : 'text-xl' }}">{{ $bullet }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    @case('fixed-strategy-cards')
        <div class="absolute inset-0 bg-white overflow-hidden">
            <div class="flex flex-col w-full h-full {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                <div class="flex justify-between items-center">
                    <div class="flex flex-row items-center justify-between {{ $mini ? 'gap-4' : 'gap-20' }}">
                        <div class="flex flex-col {{ $mini ? 'gap-2' : 'gap-10' }}">
                            <h1 class="{{ $fxH2 }} font-bold clr-txt-primary">{{ $c['heading'] ?? 'Our Strategy' }}</h1>
                            <hr class="w-3/4 border border-clr-primary">
                        </div>
                        <p class="{{ $mini ? 'text-[4px]' : 'text-lg' }} clr-txt-secondary">
                            {!! nl2br(e($c['subheading'] ?? "We understand that every business has\nunique goals for its system, such as:")) !!}
                        </p>
                    </div>
                    <x-circles />
                </div>

                <div class="grid grid-cols-5 {{ $mini ? 'gap-2' : 'gap-8' }} w-full flex-1 min-w-0">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $isDark = $i % 2 === 1;
                            $titleKey = "card{$i}_title";
                            $bodyKey = "card{$i}_body";
                        @endphp
                        <div class="flex flex-col min-w-0 rounded-lg {{ $isDark ? 'clr-primary text-base-100' : 'bg-white clr-txt-primary' }} {{ $fxCardPad }} min-h-0 w-full">
                            <div class="flex flex-col items-center {{ $mini ? 'gap-1' : 'gap-4' }} my-auto">
                                <x-icons.bulb class="{{ $fxIcon }} {{ $mini ? 'mb-0' : 'mb-2' }}" />
                                <hr class="w-full border-2 {{ $isDark ? 'border-white' : 'border-clr-primary' }}">
                                <h1 class="{{ $fxTitle }} font-bold text-center w-full">
                                    {{ $c[$titleKey] ?? "Card {$i}" }}
                                </h1>
                                <p class="{{ $fxBody }} text-center">
                                    {{ $c[$bodyKey] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @break

    @case('title')
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center {{ $pad }}">
            @if (!empty($c['heading']))
                <h1 class="{{ $h1 }} font-bold text-white mb-2">{{ $c['heading'] }}</h1>
            @endif
            @if (!empty($c['subheading']))
                <p class="{{ $sub }} text-white/60">{{ $c['subheading'] }}</p>
            @endif
        </div>
    @break

    @case('content')
        <div class="absolute inset-0 flex flex-col {{ $pad }}">
            @if (!empty($c['heading']))
                <h2 class="{{ $h2 }} font-bold text-white mb-3">{{ $c['heading'] }}</h2>
            @endif
            @if (!empty($c['body']))
                <div class="{{ $body }} text-white/75 space-y-1 overflow-hidden">
                    @foreach (explode("\n", $c['body']) as $line)
                        @php $trimmed = trim($line); @endphp
                        @if ($trimmed !== '')
                            <p>{{ $trimmed }}</p>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    @break

    @case('two-col')
        <div class="absolute inset-0 flex flex-col {{ $pad }}">
            @if (!empty($c['heading']))
                <h2 class="{{ $h2 }} font-bold text-white mb-3">{{ $c['heading'] }}</h2>
            @endif
            <div class="flex-1 grid grid-cols-2 gap-4 mt-2 overflow-hidden">
                <div class="{{ $body }} text-white/75 border-r border-white/10 pr-4">
                    @foreach (explode("\n", $c['col1'] ?? '') as $line)
                        @if (trim($line))
                            <p class="mb-0.5">{{ trim($line) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="{{ $body }} text-white/75">
                    @foreach (explode("\n", $c['col2'] ?? '') as $line)
                        @if (trim($line))
                            <p class="mb-0.5">{{ trim($line) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @break

    @case('quote')
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center {{ $pad }}">
            @if (!empty($c['quote']))
                <blockquote class="{{ $qot }} text-white/90 max-w-2xl mx-auto">{{ $c['quote'] }}</blockquote>
            @endif
            @if (!empty($c['author']))
                <p class="{{ $auth }} text-white/50 mt-4 tracking-widest uppercase">{{ $c['author'] }}</p>
            @endif
        </div>
    @break

    @default
        <div class="absolute inset-0"></div>
@endswitch
