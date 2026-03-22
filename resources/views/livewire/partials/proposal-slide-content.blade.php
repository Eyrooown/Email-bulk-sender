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
@endphp

@switch($layout)
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
