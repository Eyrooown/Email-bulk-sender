@php
    $c = isset($contentOverride) ? $contentOverride : $slide->content ?? [];
    $layout = $slide->layout ?? 'blank';

    if ($mini) {
        $h1 = 'text-[4px] leading-tight';
        $h2 = 'text-[3px] leading-tight';
        $sub = 'text-[2.5px] leading-normal';
        $body = 'text-[2px] leading-normal';
        $qot = 'text-[3.5px] leading-tight italic';
        $auth = 'text-[2px]';
        $pad = 'p-1';
        $fxPadX = 'px-2';
        $fxPadY = 'py-2';
        $fxGap = 'gap-2';
        $fxBarW = 'w-6';
        $fxH1 = 'text-[10px] leading-none';
        $fxH2 = 'text-[8px] leading-none';
        $fxH3 = 'text-[6px] leading-none';
        $fxTitle = 'text-[5px] leading-tight';
        $fxBody = 'text-[3px] leading-tight';
        $fxCardPad = 'p-2';
        $fxIcon = 'w-6 h-6';
        $fxIconSm = 'w-3 h-3';
        $fxIconMd = 'w-4 h-4';
        $fxCircle = 'h-6 w-6';
        $fxSmPad = 'px-1 py-0.5';
        $fxTagline = 'text-[4px]';
    } else {
        // Fluid typography for editor + print view (container: .proposal-slide-cqw on content column)
        $h1 = 'proposal-fluid-h1 leading-tight';
        $h2 = 'proposal-fluid-h2 leading-tight';
        $sub = 'proposal-fluid-subheading leading-relaxed';
        $body = 'proposal-fluid-body leading-relaxed';
        $qot = 'proposal-fluid-quote italic leading-snug';
        $auth = 'proposal-fluid-body-sm';
        $pad = 'p-[clamp(1.5rem,5cqw,4rem)]';
        $fxPadX = 'px-[clamp(1rem,4cqw,3rem)]';
        $fxPadY = 'py-[clamp(0.75rem,3cqw,1.5rem)]';
        $fxGap = 'gap-[clamp(0.75rem,2.5cqw,2rem)]';
        $fxBarW = 'w-[clamp(3rem,10cqw,8rem)] shrink-0';
        $fxH1 = 'proposal-fluid-fx1';
        $fxH2 = 'proposal-fluid-fx2';
        $fxH3 = 'proposal-fluid-fx3';
        $fxTitle = 'proposal-fluid-subheading';
        $fxBody = 'proposal-fluid-body';
        $fxCardPad = 'p-[clamp(0.75rem,3cqw,2rem)]';
        $fxIcon = '[width:clamp(2.25rem,8cqw,4rem)] [height:clamp(2.25rem,8cqw,4rem)]';
        $fxIconSm = '[width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]';
        $fxIconMd = '[width:clamp(1.5rem,5cqw,3rem)] [height:clamp(1.5rem,5cqw,3rem)]';
        $fxCircle = 'min-w-0 shrink-0 [width:clamp(2.375rem,7.5cqw,5rem)] [height:clamp(2.375rem,7.5cqw,5rem)]';
        $fxSmPad = 'px-[clamp(0.5rem,1.5cqw,1rem)] py-[clamp(0.25rem,1cqw,0.75rem)]';
        $fxTagline = 'proposal-fluid-subheading';
    }

    // Editor preview: fixed inset frame (same height as before); overflow-y-auto only scrolls when content exceeds it.
    $scrollableViewport = !$mini && !($printMode ?? false);
    $vpWhite = $scrollableViewport
        ? 'absolute inset-0 bg-white overflow-y-auto overflow-x-hidden'
        : 'absolute inset-0 bg-white overflow-hidden';
    $vpDark = $scrollableViewport ? 'absolute inset-0 overflow-y-auto overflow-x-hidden' : 'absolute inset-0';
    // In the editor, min-h-full keeps the slide at least one frame tall but allows content to grow so the frame scrolls.
    $fillH = $scrollableViewport ? 'min-h-full' : 'h-full';
    $fillHMd = $scrollableViewport ? 'md:min-h-full' : 'md:h-full';
@endphp

@switch($layout)

    {{-- ══════════════════════════════════════
         FIXED-COVER
    ══════════════════════════════════════ --}}
    @case('fixed-cover')
        <div class="{{ $vpWhite }}">
            <div class="flex w-full {{ $fillH }}">
                <div class="{{ $fxBarW }} clr-primary shrink-0"></div>
                <div
                    class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-row items-center {{ $mini ? 'gap-1' : 'gap-4' }}">
                            <x-logo />
                            <div class="w-px {{ $mini ? 'h-6' : 'h-12' }} clr-primary shrink-0"></div>
                            <p class="{{ $fxTagline }} clr-txt-secondary">
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
                            <p class="{{ $fxH1 }} font-extralight clr-txt-secondary">{{ $c['line3'] ?? 'PROPOSAL' }}
                            </p>
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

    {{-- ══════════════════════════════════════
         FIXED-EXECUTIVE
    ══════════════════════════════════════ --}}
    @case('fixed-executive')
        @php
            $defaults = [
                'body' =>
                    "Odecci Solutions Inc., is committed to delivering innovative, user-centric digital solutions that empower businesses to thrive in today's competitive landscape. This proposal outlines our comprehensive website development service designed to enhance your brand presence, improve customer engagement, and drive measurable business growth.\n\nOur approach combines cutting-edge technology, intuitive design, and strategic functionality to create a website that is not only visually appealing but also optimized for performance, security, and scalability. By leveraging modern frameworks and best practices, we ensure your website becomes a powerful tool for marketing, communication, and conversion.\n\nKey Highlights of Our Service:\n\nCustom Design & Branding: Tailored to reflect your unique identity and values.\nResponsive & Mobile-First Development: Seamless experience across all devices.\nSEO & Performance Optimization: Enhanced visibility and faster load times.\nContent Management System (CMS): Easy updates and scalability for future growth.\nSecurity & Compliance: Robust measures to protect data and maintain trust.\nAnalytics Integration: Actionable insights to monitor and improve performance.\n\nPartnering with Odecci means gaining a strategic ally focused on delivering a website that aligns with your business objectives, strengthens your digital footprint, and creates lasting impact. Our team ensures timely delivery, transparent communication, and ongoing support to maximize your investment.",
            ];
        @endphp
        <div class="{{ $vpWhite }}">
            <div class="flex w-full {{ $fillH }}">
                <div
                    class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                    <div class="flex justify-between items-center">
                        <p class="{{ $fxH1 }} clr-txt-primary">
                            {{ $c['heading'] ?? 'Executive Summary' }}
                        </p>
                        <x-circles />
                    </div>
                    <hr class="border-clr-primary {{ $mini ? 'w-1/3 border' : 'w-2/5 border-2' }}">
                    {{-- Match design: card overlaps right-side art; building then large navy disc on top of building corner; card reads above art where they meet --}}
                    <div
                        class="{{ $mini ? 'flex min-h-0 w-full flex-1 flex-row items-center gap-1 overflow-hidden' : 'relative flex min-h-0 w-full flex-1 flex-col gap-4 overflow-visible sm:flex-row sm:items-center sm:gap-0' }}">
                        <div
                            class="proposal-slide-cqw flex min-w-0 items-center pb-8 justify-center sm:justify-start {{ $mini ? 'z-20 w-[70%] shrink-0' : 'relative z-30 w-full max-w-full sm:w-[58%] sm:max-w-[62%] sm:shrink-0 sm:pr-1' }}">
                            <div
                                class="relative w-full max-w-full bg-white shadow-xl rounded-xl {{ $mini ? 'p-2' : $fxCardPad }}">
                                <div class="{{ $mini ? 'text-[3px]' : $fxBody }} clr-txt-primary leading-relaxed space-y-2">
                                    @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                        @if (trim($line) !== '')
                                            <p>{!! preg_replace('/\*\*(.*?)\*\*/', '<span class="font-bold">$1</span>', e(trim($line))) !!}</p>
                                        @endif
                                    @endforeach
                                    @if (!empty($c['bodyHighlights']))
                                        @php
                                            $highlights = is_array($c['bodyHighlights'])
                                                ? $c['bodyHighlights']
                                                : json_decode($c['bodyHighlights'], true) ?? [];
                                        @endphp
                                        <p class="font-bold mb-2">Key Highlights of Our Service:</p>
                                        @foreach ($highlights as $highlight)
                                            @if (trim($highlight) !== '')
                                                <p class="mb-1">• {!! preg_replace('/\*\*(.*?)\*\*/', '<span class="font-bold">$1</span>', e(trim($highlight))) !!}</p>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if (!empty($c['bodyFooter']))
                                        @foreach (explode("\n", (string) $c['bodyFooter']) as $line)
                                            @if (trim($line) !== '')
                                                <p>{!! preg_replace('/\*\*(.*?)\*\*/', '<span class="font-bold">$1</span>', e(trim($line))) !!}</p>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div
                            class="{{ $mini ? 'relative flex h-[42cqw] max-h-[88%] w-full items-end justify-end overflow-hidden' : 'proposal-slide-cqw relative z-0 flex min-h-0 min-w-0 flex-1 flex-col items-end justify-end self-stretch overflow-visible sm:-ml-[clamp(1.25rem,6cqw,4rem)]' }}">
                            <div class="relative flex max-h-full w-full items-end justify-end overflow-hidden">
                                <img src="{{ asset('images/executive-bg.png') }}" alt=""
                                    class="{{ $mini ? 'relative z-10 h-16 w-auto' : 'relative z-10 max-h-full w-auto max-w-full object-contain object-right object-bottom' }}" />
                                @if ($mini)
                                    <div
                                        class="pointer-events-none absolute z-20 rounded-full clr-primary h-20 w-20 -bottom-2 -right-2">
                                    </div>
                                @else
                                    {{-- cqw is relative to this column only; disc sits on top of building like the mockup --}}
                                    <div
                                        class="pointer-events-none absolute z-20 rounded-full clr-primary [width:clamp(6rem,72cqw,25rem)] [height:clamp(6rem,72cqw,25rem)] -bottom-[40%] -right-[35%] sm:-bottom-[15%] sm:-right-[25%]">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-WHOIS
    ══════════════════════════════════════ --}}
    @case('fixed-whois')
        @if ($mini)
            <div class="{{ $vpWhite }}">
                <div class="flex w-full {{ $fillH }}">
                    <div class="{{ $fxBarW }} clr-primary shrink-0"></div>
                    <div
                        class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                        <div class="flex justify-between items-center">
                            <x-logo />
                            <div class="flex flex-col">
                                <div class="flex flex-row justify-center items-center">
                                    <h1 class="{{ $fxH2 }} clr-txt-secondary mt-1">
                                        {{ $c['top_heading'] ?? 'OUR STRATEGY' }}</h1>
                                    <x-circles />
                                </div>
                                <hr class="w-1/2 mt-2 border border-clr-primary">
                            </div>
                        </div>
                        <div class="flex flex-row justify-between w-full flex-1 min-w-0">
                            <div class="flex flex-col flex-1 min-w-0">
                                @php $heading = $c['heading'] ?? null; @endphp
                                <h1 class="font-medium text-[6px] clr-txt-primary">
                                    @if ($heading)
                                        {!! nl2br(e(str_replace('\n', "\n", $heading))) !!}
                                    @else
                                        Who is<br>Odecci?
                                    @endif
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @php
                $whoisWebsite = trim((string) ($c['website'] ?? ''));
                if ($whoisWebsite === '') {
                    $whoisWebsite = 'https://odecci.com';
                }
                $whoisHref = preg_match('#^https?://#i', $whoisWebsite)
                    ? $whoisWebsite
                    : 'https://' . ltrim($whoisWebsite, '/');
            @endphp
            <div class="{{ $vpWhite }}">
                <div class="flex w-full {{ $fillH }} flex-col md:min-h-0 {{ $fillHMd }} md:flex-row">
                    <div class="h-2 w-full shrink-0 clr-primary md:h-auto md:w-[clamp(3rem,10cqw,8rem)] md:min-h-full"></div>
                    <div
                        class="proposal-slide-cqw flex min-h-0 w-full min-w-0 flex-1 flex-col gap-[clamp(0.75rem,2.5cqw,1.5rem)] px-[clamp(1rem,4cqw,3rem)] py-[clamp(0.75rem,3cqw,1.5rem)]">
                        {{-- 3-column header: logo | centered title + rule | circles (matches preview at any width) --}}
                        <div
                            class="grid w-full grid-cols-1 gap-[clamp(0.75rem,3cqw,1.5rem)] sm:grid-cols-[minmax(0,auto)_1fr_minmax(0,auto)] sm:items-center sm:gap-x-[clamp(0.75rem,3cqw,1.75rem)]">
                            <div class="flex justify-center sm:justify-self-start">
                                <div
                                    class="flex flex-row items-center gap-4 [&_img]:max-h-[clamp(2rem,12cqw,4rem)] [&_img]:w-auto">
                                    <x-logo />
                                </div>
                            </div>
                            <div
                                class="mt-[clamp(0.25rem,1.5cqw,1.5rem)] flex min-w-0 flex-col items-center text-center sm:justify-self-center">
                                <h1 class="proposal-fluid-fx2 clr-txt-secondary">
                                    {{ $c['top_heading'] ?? 'OUR STRATEGY' }}</h1>
                                <hr class="mx-auto mt-[clamp(0.375rem,1.5cqw,1rem)] w-3/4 max-w-xl border-2 border-clr-primary">
                            </div>
                            <div
                                class="flex justify-center sm:justify-self-end sm:justify-end [&>div]:h-[clamp(2.25rem,8cqw,3rem)] [&>div]:w-[clamp(7rem,22cqw,10rem)] [&>div]:gap-[clamp(0.1rem,0.4cqw,0.125rem)] [&>div_.rounded-full]:!h-[clamp(0.75rem,2.5cqw,1rem)] [&>div_.rounded-full]:!w-[clamp(0.75rem,2.5cqw,1rem)]">
                                <x-circles />
                            </div>
                        </div>
                        <div
                            class="flex min-h-0 flex-1 flex-col gap-[clamp(1rem,3.5cqw,1.75rem)] md:flex-row md:justify-between md:gap-[clamp(0.5rem,2cqw,0.75rem)]">
                            <div class="flex min-w-0 flex-1 flex-col justify-center gap-[clamp(0.75rem,2.5cqw,1.25rem)]">
                                @php $heading = $c['heading'] ?? null; @endphp
                                <h1 class="proposal-fluid-fx1 clr-txt-primary">
                                    @if ($heading)
                                        {!! nl2br(e(str_replace('\n', "\n", $heading))) !!}
                                    @else
                                        Who is<br>Odecci?
                                    @endif
                                </h1>
                                <div class="proposal-fluid-body space-y-2 clr-txt-primary">
                                    @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                        @if (trim($line) !== '')
                                            <p>{{ trim($line) }}</p>
                                        @endif
                                    @endforeach
                                </div>
                                <p class="proposal-fluid-body clr-txt-primary">
                                    Visit our website:
                                    <a href="{{ $whoisHref }}" class="underline decoration-solid">{{ $whoisWebsite }}</a>
                                    to learn more
                                </p>
                            </div>
                            <div class="flex min-w-0 flex-1 flex-row items-center justify-center">
                                <div class="grid w-full grid-cols-1 gap-[clamp(0.75rem,3cqw,2rem)] sm:grid-cols-2">
                                    @foreach ($c['bullets'] ?? [] as $bullet)
                                        @php
                                            $bulletText = is_array($bullet) ? $bullet['text'] ?? '' : $bullet;
                                            $bulletIcon = is_array($bullet) ? $bullet['icon'] ?? 'diamond' : 'diamond';
                                        @endphp
                                        <div class="flex flex-row items-center gap-[clamp(0.5rem,2.5cqw,1.25rem)]">
                                            <div
                                                class="flex shrink-0 items-center justify-center rounded-full clr-bg-secondary text-white [width:clamp(2.375rem,7.5cqw,4rem)] [height:clamp(2.375rem,7.5cqw,4rem)]">
                                                @switch($bulletIcon)
                                                    @case('paperplane')
                                                        <x-icons.proposal.paperplane
                                                            classes="shrink-0 [width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]" />
                                                    @break

                                                    @case('chart')
                                                        <x-icons.proposal.chart
                                                            classes="shrink-0 [width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]" />
                                                    @break

                                                    @case('calendar-check')
                                                        <x-icons.proposal.calendar-check
                                                            classes="shrink-0 [width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]" />
                                                    @break

                                                    @case('bulb')
                                                        <x-icons.proposal.bulb
                                                            classes="shrink-0 [width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]" />
                                                    @break

                                                    @default
                                                        <x-icons.proposal.diamond
                                                            classes="shrink-0 [width:clamp(0.875rem,2.8cqw,1.5rem)] [height:clamp(0.875rem,2.8cqw,1.5rem)]" />
                                                @endswitch
                                            </div>
                                            <p class="proposal-fluid-body font-bold leading-snug clr-txt-secondary">
                                                {{ $bulletText }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @break

    {{-- ══════════════════════════════════════
         FIXED-STRATEGY-CARDS
    ══════════════════════════════════════ --}}
    @case('fixed-strategy-cards')
        @php
            $strategyDefaults = [
                1 => [
                    'title' => 'Hand Tailored Solutions',
                    'body' => "Design websites that are uniquely customized to align with each client's specific business needs, from branded interfaces to intricate technical functionalities, ensuring a perfect fit for their operations.",
                ],
                2 => [
                    'title' => 'Enhance Client Collaboration',
                    'body' =>
                        'Integrate closely with clients throughout the support process, fostering a partnership that incorporates their vision and feedback to create solutions that reflect their goals.',
                ],
                3 => [
                    'title' => 'Boost Business Performance',
                    'body' =>
                        'Develop a maintenance and support process that drives measurable outcomes, such as increased website performance and improved visibility.',
                ],
                4 => [
                    'title' => 'Ensure Exceptional User Experience',
                    'body' =>
                        'Create intuitive, visually appealing interfaces that enhance user engagement and satisfaction, making the application both functional and accessible for end-users.',
                ],
                5 => [
                    'title' => 'Provide Strategic Implementation',
                    'body' =>
                        'Support clients with comprehensive strategies, including case studies and development roadmaps, to ensure seamless deployment and long-term success of the website.',
                ],
            ];
        @endphp
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $fxGap }}">
                <div class="flex justify-between items-center shrink-0">
                    <div class="flex flex-row items-center justify-between {{ $mini ? 'gap-4' : 'gap-20' }}">
                        <div class="flex flex-col {{ $mini ? 'gap-2' : 'gap-4' }}">
                            <h1 class="{{ $mini ? $fxH2 : 'text-6xl' }} font-bold clr-txt-primary">
                                {{ $c['heading'] ?? 'Our Strategy' }}</h1>
                            <hr class="w-3/4 border border-clr-primary">
                        </div>
                        <p class="{{ $mini ? 'text-[4px]' : 'text-lg' }} clr-txt-secondary">{!! nl2br(e($c['subheading'] ?? "We understand that every business has\nunique goals for its system, such as:")) !!}</p>
                    </div>
                    <x-circles />
                </div>
                <div class="grid grid-cols-5 {{ $mini ? 'gap-2' : 'gap-4 mt-5 items-stretch h-3/5' }} w-full">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $isDark = $i % 2 === 1;
                            $cardTitle = trim((string) ($c["card{$i}_title"] ?? $strategyDefaults[$i]['title']));
                            $titleWords = preg_split('/\s+/', $cardTitle) ?: [];
                            $iconFromTitle = \Illuminate\Support\Str::slug(implode(' ', array_slice($titleWords, 0, 2)));
                            $iconName = file_exists(resource_path("views/components/icons/proposal/{$iconFromTitle}.blade.php")) ? $iconFromTitle : 'diamond';
                        @endphp
                        <div
                            class="flex flex-col min-w-0 {{ $isDark ? 'clr-primary text-white' : 'bg-white clr-txt-primary shadow-md' }} {{ $mini ? 'rounded-lg p-2' : 'rounded-2xl p-6 w-full h-full' }}">
                            <div class="flex flex-col items-center {{ $mini ? 'gap-1' : 'gap-3' }}">
                                <x-dynamic-component :component="'icons.proposal.' . $iconName"
                                    :classes="$mini ? $fxIcon : 'w-12 h-12 mb-1'" />
                                <hr class="w-full border {{ $isDark ? 'border-white' : 'border-clr-primary' }}">
                                <h1 class="{{ $mini ? $fxTitle : 'text-base' }} font-bold text-center w-full">
                                    {{ $c["card{$i}_title"] ?? $strategyDefaults[$i]['title'] }}</h1>
                                <p class="{{ $mini ? $fxBody : 'text-xs leading-snug' }} text-center">
                                    {{ $c["card{$i}_body"] ?? $strategyDefaults[$i]['body'] }}</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-PROBLEM-STATEMENT
    ══════════════════════════════════════ --}}
    @case('fixed-problem-statement')
        @php
            $problemItems = array_values(
                array_filter([
                    $c['problem1'] ?? null,
                    $c['problem2'] ?? null,
                    $c['problem3'] ?? null,
                    $c['problem4'] ?? null,
                    $c['problem5'] ?? null,
                ]),
            );
            if (empty($problemItems)) {
                $problemItems = [
                    'Lack of Professional Online Presence',
                    'Poor User Experience and Navigation',
                    'Limited Scalability and Content Management',
                    'Low Search Engine Visibility',
                    'Security Vulnerabilities and Compliance Risks',
                ];
            }
        @endphp
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH1 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Problem Statement' }}</h1>
                        <hr class="w-2/5 border-t border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <p class="{{ $fxBody }} leading-relaxed clr-txt-primary max-w-5xl shrink-0">
                    {{ $c['body'] ?? "In today's digital-first environment, businesses face numerous obstacles that hinder their ability to maintain a strong online presence." }}
                </p>
                <div class="flex flex-col w-full mx-auto {{ $mini ? 'gap-1 mt-1' : 'gap-10 mt-4' }} flex-1">
                    <div class="flex items-center gap-3 w-full">
                        <div class="flex-1 border-t border-dashed border-gray-400"></div>
                        <div
                            class="clr-primary text-white {{ $mini ? 'px-2 py-1 text-[3px]' : 'p-4 text-xl' }} rounded-full font-bold tracking-widest uppercase whitespace-nowrap">
                            {{ $c['pill'] ?? 'Top 5 most common problems encountered' }}
                        </div>
                        <div class="flex-1 border-t border-dashed border-gray-400"></div>
                    </div>
                    <div class="grid grid-cols-5 {{ $mini ? 'gap-1' : 'gap-2' }} w-full">
                        @foreach ($problemItems as $n => $label)
                            <div
                                class="bg-white rounded-xl flex flex-col justify-between text-center shadow-md {{ $mini ? 'h-10' : 'h-40' }} overflow-visible">
                                <p
                                    class="{{ $fxBody }} font-bold clr-txt-primary leading-tight {{ $mini ? 'px-1 pt-1' : 'px-3 pt-4' }}">
                                    {{ $label }}</p>
                                <div class="relative w-full {{ $mini ? 'h-3' : 'h-8' }} mt-2">
                                    <div
                                        class="absolute w-full inset-x-0 bottom-0 {{ $mini ? 'h-2' : 'h-4' }} {{ ($n + 1) % 2 === 1 ? 'clr-primary' : 'clr-bg-secondary' }}
                                        {{ $n === 0 ? 'rounded-bl-xl' : '' }} {{ $n === 4 ? 'rounded-br-xl' : '' }}">
                                    </div>
                                    <div class="absolute inset-x-0 top-0 flex justify-center">
                                        <span
                                            class="inline-flex {{ $mini ? 'h-4 w-4 text-[3px]' : 'h-10 w-10 text-sm' }} items-center justify-center rounded-full bg-white shadow-md border border-gray-200 font-bold clr-txt-primary">
                                            {{ $n + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-CUSTOM-SOLUTION
    ══════════════════════════════════════ --}}
    @case('fixed-custom-solution')
        @php
            $boxClasses = ['clr-primary', 'clr-bg-secondary', 'clr-bg-light', 'clr-bg-secondary', 'clr-primary'];
            $iconClasses = ['text-white', 'text-white', 'clr-txt-primary', 'text-white', 'text-white'];
            $solutionItems = [];
            for ($i = 1; $i <= 5; $i++) {
                $t = $c["solution{$i}_title"] ?? '';
                $d = $c["solution{$i}_desc"] ?? '';
                if ($t || $d) {
                    $solutionItems[] = [
                        'boxClass' => $boxClasses[$i - 1],
                        'iconClass' => $iconClasses[$i - 1],
                        'title' => $t,
                        'desc' => $d,
                    ];
                }
            }
            if (empty($solutionItems)) {
                $solutionItems = [
                    [
                        'boxClass' => 'clr-primary',
                        'iconClass' => 'text-white',
                        'title' => 'Seamless User Experience',
                        'desc' => 'Intuitive navigation and mobile-first design for maximum engagement.',
                    ],
                    [
                        'boxClass' => 'clr-bg-secondary',
                        'iconClass' => 'text-white',
                        'title' => 'Scalable Architecture',
                        'desc' => 'Built on a robust CMS for easy updates and future growth.',
                    ],
                    [
                        'boxClass' => 'clr-bg-light',
                        'iconClass' => 'clr-txt-primary',
                        'title' => 'Enhanced Visibility',
                        'desc' => 'Integrated SEO strategies to boost search rankings and attract organic traffic.',
                    ],
                    [
                        'boxClass' => 'clr-bg-secondary',
                        'iconClass' => 'text-white',
                        'title' => 'Enterprise-Level Security',
                        'desc' => 'Strong protection against cyber threats and compliance with data standards.',
                    ],
                    [
                        'boxClass' => 'clr-primary',
                        'iconClass' => 'text-white',
                        'title' => 'Actionable Insights',
                        'desc' => 'Analytics integration for informed decision-making and continuous improvement.',
                    ],
                ];
            }
        @endphp
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-1 flex-col justify-between w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH1 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Our Custom Solution' }}</h1>
                        <hr class="w-2/5 border-t border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <p class="{{ $fxBody }} leading-relaxed clr-txt-secondary max-w-5xl shrink-0">{{ $c['body'] ?? '' }}
                </p>
                <div
                    class="grid grid-cols-5 {{ $mini ? 'gap-1' : 'gap-4' }} w-full {{ $mini ? 'mt-1' : 'mt-2' }} flex-1 items-start">
                    @foreach ($solutionItems as $item)
                        <div class="flex flex-col items-center gap-0 h-full">
                            <div
                                class="{{ $item['boxClass'] }} rounded-xl flex items-center justify-center {{ $mini ? 'px-1 py-2' : 'px-4 py-6' }} w-full">
                                <x-icons.proposal.bulb class="{{ $fxIconMd }} shrink-0 {{ $item['iconClass'] }}" />
                            </div>
                            <div class="w-px {{ $mini ? 'h-2' : 'h-6' }} border-l border-dashed border-gray-400"></div>
                            <h2 class="{{ $fxBody }} font-bold clr-txt-primary text-center">{{ $item['title'] }}</h2>
                            <p
                                class="{{ $mini ? 'text-[2px]' : 'text-xs' }} clr-txt-secondary text-center leading-snug px-0.5 mt-1">
                                {{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-SCOPE-BASIC / BUSINESS / STORE
    ══════════════════════════════════════ --}}
    @case('fixed-scope-basic')
    @case('fixed-scope-business')

    @case('fixed-scope-store')
        @php
            $tags = $c['tags'] ?? [];
            $whatYouGet = $c['whatYouGet'] ?? [];
            $inclusions = $c['inclusions'] ?? [];
            if (is_string($tags)) {
                $tags = json_decode($tags, true) ?? [];
            }
            if (is_string($whatYouGet)) {
                $whatYouGet = json_decode($whatYouGet, true) ?? [];
            }
            if (is_string($inclusions)) {
                $inclusions = json_decode($inclusions, true) ?? [];
            }

            if ($layout === 'fixed-scope-basic' && empty($whatYouGet)) {
                $c['packageName'] ??= 'Basic Website Package';
                $c['idealFor'] ??=
                    'Small to Medium Businesses looking for a professional online presence without complexity.';
                $c['revision'] ??= '1 Design Revision';
                $c['benefit'] ??= "What You'll Get";
                $tags = ['Tailored Design', '2 Weeks Delivery'];
                $whatYouGet = [
                    ['title' => '1-3', 'desc' => 'Mobile Friendly Design'],
                    [
                        'title' => 'Content Management System (CMS)',
                        'desc' => 'Effortless content updates and scalability.',
                    ],
                    ['title' => 'Basic SEO Setup', 'desc' => 'Improve search visibility and attract organic traffic.'],
                    [
                        'title' => 'Google Analytics Integration',
                        'desc' => 'Track performance and user behavior for data-driven decisions.',
                    ],
                ];
                $inclusions = [
                    ['title' => '1 Month Free Support', 'desc' => 'Post-launch assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Track visitor behavior and performance.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Enhance your local search presence.'],
                    [
                        'title' => '3 Personalized Business Email Addresses',
                        'desc' => 'Professional communication for your team.',
                    ],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    [
                        'title' => 'Free Domain (.com) with SSL for 1 Year',
                        'desc' => 'Secure and professional online identity.',
                    ],
                    ['title' => 'Free CMS Training', 'desc' => '1-day, 2-hour live session to empower your team.'],
                ];
            }
            if ($layout === 'fixed-scope-business' && empty($whatYouGet)) {
                $c['packageName'] ??= 'Business Website Package';
                $c['idealFor'] ??=
                    'Scaling businesses that want their brand to stand out, increase traffic, and showcase products and services with a professional, feature-rich website.';
                $c['revision'] ??= '2 Design Revision';
                $c['benefit'] ??= "You'll Get All from basic plus:";
                $tags = ['Tailored Design', '4 Weeks Delivery', 'CRM Integration'];
                $whatYouGet = [
                    ['title' => '3-6', 'desc' => 'Mobile-Friendly Pages'],
                    ['title' => 'Products/Services Page', 'desc' => 'Highlight your offerings with engaging layouts.'],
                    ['title' => 'Blog & News Page', 'desc' => 'Share updates, insights, and boost SEO.'],
                    ['title' => 'Content Management System (CMS)', 'desc' => 'Easy content updates and scalability.'],
                    ['title' => 'CRM Integration (HubSpot)', 'desc' => 'Streamline customer relationship management.'],
                    ['title' => 'Advanced SEO Setup', 'desc' => 'Drive organic traffic and improve search rankings.'],
                ];
                $inclusions = [
                    ['title' => '2 Month Free Support', 'desc' => 'Extended assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Monitor performance and user behavior.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Strengthen your local presence.'],
                    [
                        'title' => '5 Personalized Business Email Addresses',
                        'desc' => 'Professional communication for your team.',
                    ],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    [
                        'title' => 'Free Domain (.com) with SSL for 1 Year',
                        'desc' => 'Secure and professional online identity.',
                    ],
                    ['title' => 'Free CMS Training', 'desc' => '2-days with 2-hour live session to empower your team.'],
                ];
            }
            if ($layout === 'fixed-scope-store' && empty($whatYouGet)) {
                $c['packageName'] ??= 'Online Store Website Package';
                $c['idealFor'] ??= 'Retailers, wholesalers, and brands that want to sell online with ease.';
                $c['revision'] ??= '3 Design Revision';
                $c['benefit'] ??= "You'll Get All from basic and business plus:";
                $tags = ['Tailored Design', '8 Weeks Delivery', 'CRM Integration', 'Payment Integration'];
                $whatYouGet = [
                    [
                        'title' => 'Unlimited Mobile-Friendly Design',
                        'desc' => 'Fully responsive layouts for an exceptional user experience across all devices.',
                    ],
                    [
                        'title' => 'Full Inventory & Order Management',
                        'desc' => 'Manage stock, orders, and fulfillment seamlessly.',
                    ],
                    [
                        'title' => 'Discounts, Coupons & Promotions',
                        'desc' => 'Engage customers with attractive offers.',
                    ],
                    ['title' => 'Cart + Checkout Page', 'desc' => 'Smooth and secure shopping experience.'],
                    [
                        'title' => 'Maintainable Shipping Rates',
                        'desc' => 'Flexible shipping options for your customers.',
                    ],
                    ['title' => 'Online Payment Integration', 'desc' => 'Accept payments securely and conveniently.'],
                    ['title' => 'Customer Dashboard', 'desc' => 'Empower customers with account management tools.'],
                ];
                $inclusions = [
                    ['title' => '3 Month Free Support', 'desc' => 'Extended assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Track visitor behavior and performance.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Enhance your local search presence.'],
                    [
                        'title' => '10 Personalized Business Email Addresses',
                        'desc' => 'Professional communication for your team.',
                    ],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    [
                        'title' => 'Free Domain (.com) with SSL for 1 Year',
                        'desc' => 'Secure and professional online identity.',
                    ],
                    ['title' => 'Free CMS Training', 'desc' => '3-days, 2-hour live session to empower your team.'],
                ];
            }
        @endphp
        <div
            class="absolute inset-0 bg-white {{ $mini ? 'overflow-hidden' : ($printMode ?? false ? 'overflow-hidden' : 'overflow-y-auto') }}">
            <x-scope-card :mini="$mini" :packageName="$c['packageName'] ?? 'Package'" :idealFor="$c['idealFor'] ?? ''" :revision="$c['revision'] ?? ''" :tags="$tags"
                :benefit="$c['benefit'] ?? 'What You\'ll Get'" :whatYouGet="$whatYouGet" :inclusions="$inclusions" />
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-TERMS
    ══════════════════════════════════════ --}}
    @case('fixed-terms')
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-1 flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH3 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Terms And Condition' }}</h1>
                        <hr class="w-2/5 border-t border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <div class="flex flex-col {{ $mini ? 'gap-1' : 'gap-4' }} mt-1">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-2' }}">
                        <p class="{{ $fxBody }} font-bold clr-txt-primary">Payment Terms</p>
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="clr-primary text-white">
                                    <th
                                        class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} text-left w-1/4 font-bold italic">
                                        Percentage</th>
                                    <th
                                        class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} text-left font-bold italic">
                                        Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} italic clr-txt-primary">
                                        {{ $c['payment_row1_pct'] ?? '50% Downpayment' }}</td>
                                    <td
                                        class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} italic clr-txt-secondary">
                                        {{ $c['payment_row1_desc'] ?? 'Upon contract signing' }}</td>
                                </tr>
                                <tr>
                                    <td class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} italic clr-txt-primary">
                                        {{ $c['payment_row2_pct'] ?? '50% Deployment' }}</td>
                                    <td
                                        class="{{ $mini ? 'text-[2px] px-1 py-0.5' : 'px-6 py-3' }} italic clr-txt-secondary">
                                        {{ $c['payment_row2_desc'] ?? 'Upon turnover and project acceptance.' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @foreach ([['key' => 'terms', 'label' => 'Terms and Condition'], ['key' => 'client', 'label' => 'Client Responsibilities:'], ['key' => 'liability', 'label' => 'Limitation of Liability:']] as $section)
                        @php
                            $bullets = $c[$section['key'] . '_bullets'] ?? [];
                            if (is_string($bullets)) {
                                $bullets = json_decode($bullets, true) ?? [];
                            }
                        @endphp
                        @if (!empty($bullets))
                            <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                                <p class="{{ $fxBody }} font-bold clr-txt-primary">{{ $section['label'] }}</p>
                                <ul class="flex flex-col gap-1">
                                    @foreach ($bullets as $bullet)
                                        <li class="{{ $fxBody }} clr-txt-secondary">• {{ $bullet }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                    <div class="flex flex-col gap-1">
                        <p class="{{ $fxBody }} font-bold clr-txt-primary">Execution of Service Level Agreement:</p>
                        <p class="{{ $fxBody }} clr-txt-secondary leading-relaxed">
                            {{ $c['sla_text'] ?? 'Upon approval of this proposal, a detailed Service Level Agreement (SLA) will be executed to formalize the terms of service, ensuring clarity on the delivery, management, and support of the website throughout the contract period.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-PROJECTS
    ══════════════════════════════════════ --}}
    @case('fixed-projects')
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-1 flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH3 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Some of our Website Projects' }}</h1>
                        <hr class="w-3/5 border-2 border-t {{ $mini ? 'mt-1' : 'mt-4' }} border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <div class="grid grid-cols-2 grid-rows-2 {{ $mini ? 'gap-1' : 'gap-4' }} mt-1 flex-1">
                    <div class="w-full h-full overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/htms.png') }}" alt="{{ $c['project1_label'] ?? 'HTMS Website' }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full h-full overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/ysk.png') }}" alt="{{ $c['project2_label'] ?? 'Project 2' }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-full h-full overflow-hidden rounded-lg shadow-md">
                        <img src="{{ asset('images/st-regis.png') }}" alt="{{ $c['project3_label'] ?? 'Project 3' }}"
                            class="w-full h-full object-cover">
                    </div>
                    <div
                        class="flex items-center justify-center w-full h-full clr-primary text-white font-bold rounded-lg shadow-md {{ $fxBody }}">
                        {{ $c['portfolio_label'] ?? 'View Our Portfolio >>' }}
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-ORGANIZATIONS
    ══════════════════════════════════════ --}}
    @case('fixed-organizations')
        @php
            $orgs = $c['organizations'] ?? [];
            if (is_string($orgs)) {
                $orgs = json_decode($orgs, true) ?? [];
            }
            if (empty($orgs)) {
                $orgs = array_map(fn($i) => "Organization {$i}", range(1, 11));
            }
        @endphp
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-1 flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH3 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Organizations we work with' }}</h1>
                        <hr class="w-3/5 border-2 border-t {{ $mini ? 'mt-1' : 'mt-4' }} border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <div class="grid grid-cols-3 grid-rows-4 {{ $mini ? 'gap-1' : 'gap-4' }} mt-4">
                    @foreach ($organizations as $org)
                        <div class="flex justify-center items-center">
                            <img
                                src="{{ asset($org['image']) }}"
                                alt="{{ $org['name'] }}"
                                class="w-32 h-32 object-contain"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-TESTIMONIAL
    ══════════════════════════════════════ --}}
    @case('fixed-testimonial')
        <div class="{{ $vpWhite }}">
            <div
                class="proposal-slide-cqw min-w-0 flex flex-1 flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $fxPadY }} {{ $mini ? 'gap-1' : 'gap-3' }}">
                <div class="flex justify-between items-start shrink-0 gap-4">
                    <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-1.5' }}">
                        <h1 class="{{ $fxH3 }} font-bold clr-txt-primary tracking-tight">
                            {{ $c['heading'] ?? 'Testimonial' }}</h1>
                        <hr class="w-3/5 border-2 border-t {{ $mini ? 'mt-1' : 'mt-4' }} border-clr-primary">
                    </div>
                    <x-circles />
                </div>
                <div class="flex flex-row h-3/5 justify-center items-center">
                    <div
                        class="{{ $mini ? '-space-x-3' : '-space-x-12' }} flex flex-row justify-center items-center h-full w-3/5 mt-4">
                        <div
                            class="clr-primary rounded-3xl w-full h-full flex items-center justify-center {{ $mini ? 'p-1' : 'p-6' }}">
                            <p class="text-gray-300 text-center {{ $fxBody }}">
                                {{ $c['testimonial1'] ?? 'Testimonial 1' }}</p>
                        </div>
                        <div
                            class="clr-bg-light rounded-3xl w-full h-full flex items-center justify-center {{ $mini ? 'p-1' : 'p-6' }}">
                            <p class="text-gray-500 text-center {{ $fxBody }}">
                                {{ $c['testimonial2'] ?? 'Testimonial 2' }}</p>
                        </div>
                        <div
                            class="clr-primary rounded-3xl w-full h-full flex items-center justify-center {{ $mini ? 'p-1' : 'p-6' }}">
                            <p class="text-gray-300 text-center {{ $fxBody }}">
                                {{ $c['testimonial3'] ?? 'Testimonial 3' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-WHY-CUSTOMIZE
    ══════════════════════════════════════ --}}
    @case('fixed-why-customize')
        @php
            $whyItems = $c['why_items'] ?? [];
            if (is_string($whyItems)) {
                $whyItems = json_decode($whyItems, true) ?? [];
            }
            if (empty($whyItems)) {
                $whyItems = [
                    [
                        'title' => 'Secure & High-Performance Infrastructure',
                        'desc' =>
                            'Speed, reliability, and security are non-negotiable. Odecci builds applications on optimized, secure infrastructure to ensure fast load times, seamless accessibility, and robust protection against threats, giving your users a dependable experience.',
                        'titleBg' => 'clr-bg-secondary',
                        'titleText' => 'text-white',
                    ],
                    [
                        'title' => 'Goal-Oriented Solutions',
                        'desc' =>
                            "We don't just build applications—we create tools that align seamlessly with your business objectives. Our focus on usability, accessibility, and functionality ensures your application delivers measurable value and supports your long-term vision.",
                        'titleBg' => 'bg-white',
                        'titleText' => 'clr-txt-primary',
                    ],
                    [
                        'title' => 'Client-Centric Approach',
                        'desc' =>
                            'At Odecci, your success is our priority. We collaborate closely with you, offering expert guidance on decision-making, risk management, and ideation. From concept to execution, we provide tailored suggestions to bring your vision to life while mitigating challenges.',
                        'titleBg' => 'bg-white',
                        'titleText' => 'clr-txt-primary',
                    ],
                    [
                        'title' => 'Purposeful, Modern Design',
                        'desc' =>
                            "Our designs go beyond aesthetics. Every element of your application is strategically crafted to enhance user experience and align with the system's overall functionality. The result is an intuitive, visually appealing application that resonates with your audience.",
                        'titleBg' => 'clr-bg-secondary',
                        'titleText' => 'text-white',
                    ],
                    [
                        'title' => 'Cutting-Edge Technologies',
                        'desc' =>
                            'Odecci leverages the latest, industry-leading technologies to build robust, scalable, and future-proof applications. Our solutions are designed to remain relevant and adaptable as your business evolves.',
                        'titleBg' => 'bg-white',
                        'titleText' => 'clr-txt-primary',
                    ],
                ];
            }
        @endphp
        <div class="{{ $vpWhite }}">
            <div class="flex w-full {{ $fillH }}">
                <div class="flex flex-col w-1/2 {{ $mini ? 'px-3 py-2' : 'px-10 py-5' }} justify-between">
                    <div class="flex flex-col {{ $mini ? 'gap-1' : 'gap-4' }}">
                        <x-circles />
                        <h1 class="{{ $fxH3 }} font-bold clr-txt-primary tracking-tight leading-tight">
                            {{ $c['heading'] ?? 'Why Your Business Needs a Customized Application' }}</h1>
                        <p class="{{ $fxBody }} clr-txt-secondary leading-relaxed">
                            {{ $c['body'] ?? 'Every business is unique, even if daily operations seem similar across industries...' }}
                        </p>
                    </div>
                    <div class="flex flex-col {{ $mini ? 'gap-1' : 'gap-3' }}">
                        <hr class="{{ $mini ? 'w-3' : 'w-10' }} border-t-2 border-clr-primary">
                        <p class="{{ $fxBody }} clr-txt-secondary leading-relaxed">
                            {{ $c['footer'] ?? "At Odecci, we don't just build systems, we create platforms that work as smart, strategic tools for your business." }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex flex-col justify-center items-center w-1/2 clr-primary {{ $fillH }} {{ $mini ? 'px-2 py-2' : 'px-8 py-6' }} gap-6">
                    <div
                        class="grid grid-cols-2 justify-center items-center {{ $mini ? 'gap-x-2 gap-y-1' : 'gap-x-6 gap-y-5' }} w-full">
                        @foreach ($whyItems as $index => $item)
                            <div
                                class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-2' }} {{ $index === 4 ? 'col-span-2 w-1/2' : '' }}">
                                <div class="relative inline-block">
                                    <div
                                        class="{{ $item['titleBg'] ?? 'bg-white' }} {{ $item['titleText'] ?? 'clr-txt-primary' }} {{ $fxSmPad }} rounded-lg {{ $fxBody }} font-bold leading-snug">
                                        {{ $item['title'] }}
                                    </div>
                                    <div
                                        class="absolute left-5 -bottom-2 w-0 h-0 border-l-[8px] border-l-transparent border-r-[8px] border-r-transparent border-t-[10px] {{ in_array($index, [0, 3]) ? 'border-t-[#2d4a6b]' : 'border-t-white' }}">
                                    </div>
                                </div>
                                <p class="{{ $mini ? 'text-[2px]' : 'text-xs' }} text-gray-300 leading-relaxed mt-2">
                                    {{ $item['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-GUIDANCE
    ══════════════════════════════════════ --}}
    @case('fixed-guidance')
        <div class="{{ $vpWhite }}">
            <div class="flex flex-col items-center w-full {{ $fillH }}">
                <div
                    class="w-full flex-1 {{ $mini ? 'px-3 pt-2' : 'px-10 pt-8' }} flex flex-row items-start overflow-hidden">
                    <div class="w-1/2 h-4/5 rounded-2xl overflow-hidden shrink-0">
                        <img src="{{ asset('images/guidance.png') }}" alt="Guidance" class="w-full h-full object-cover">
                    </div>
                    <div
                        class="clr-primary rounded-3xl {{ $mini ? 'px-3 py-3 -ml-4 mt-2' : 'px-10 py-10 -ml-16 mt-8' }} flex flex-col items-center justify-center text-center {{ $mini ? 'gap-1' : 'gap-4' }} h-2/5 w-5/12 self-start">
                        <h1 class="{{ $fxH3 }} font-light text-white tracking-tight">
                            {{ $c['heading'] ?? 'Need guidance?' }}</h1>
                        <div class="flex flex-col gap-1">
                            <p class="{{ $fxBody }} text-gray-300">
                                {{ $c['subtext1'] ?? "We're here to help. Contact us for a free consultation." }}</p>
                            <p class="{{ $fxBody }} text-gray-300">
                                {{ $c['subtext2'] ?? 'Or click this link and book now:' }}</p>
                        </div>
                        <span
                            class="{{ $fxBody }} text-blue-400 underline">{{ $c['link'] ?? 'https://odecci.com/consultation/' }}</span>
                    </div>
                </div>
                <div class="flex justify-end w-full {{ $mini ? 'pb-1 pr-2' : 'pb-4 pr-6' }}">
                    <x-circles />
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         FIXED-CONTACT
    ══════════════════════════════════════ --}}
    @case('fixed-contact')
        <div class="{{ $vpWhite }}">
            <div class="flex w-full {{ $fillH }}">
                <div class="{{ $fxBarW }} clr-primary shrink-0 {{ $fillH }}"></div>
                <div
                    class="proposal-slide-cqw min-w-0 flex flex-col w-full {{ $fillH }} {{ $fxPadX }} {{ $mini ? 'py-2' : 'py-6' }} justify-between">
                    <div class="flex justify-between items-start w-full">
                        <x-logo />
                    </div>
                    <div class="flex row justify-between items-center w-full gap-4">
                        <div class="flex flex-col leading-none">
                            <h1
                                class="{{ $fxH1 }} font-bold clr-txt-primary tracking-tight">{!! $c['heading'] !!}</h1>
                        </div>
                        <img src="{{ asset('images/icon-dark.png') }}" alt="Logo"
                            class="{{ $mini ? 'w-12' : 'w-1/3' }} h-auto object-contain">
                    </div>
                    <div class="flex flex-row items-start {{ $mini ? 'gap-3' : 'gap-16' }} pb-2">
                        <div class="flex flex-col {{ $mini ? 'gap-1' : 'gap-3' }}">
                            <div class="flex items-center {{ $mini ? 'gap-1' : 'gap-3' }}">
                                <x-icons.proposal.bulb class="{{ $fxIconSm }} clr-txt-primary shrink-0" />
                                <div class="flex flex-col border-b border-gray-400 pb-1 min-w-0">
                                    <span
                                        class="{{ $fxBody }} clr-txt-primary">{{ $c['email1'] ?? 'info@odecci.com' }}</span>
                                    <span
                                        class="{{ $fxBody }} clr-txt-primary">{{ $c['email2'] ?? 'sales@odecci.com' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center {{ $mini ? 'gap-1' : 'gap-3' }}">
                                <x-icons.proposal.bulb class="{{ $fxIconSm }} clr-txt-primary shrink-0" />
                                <span
                                    class="{{ $fxBody }} clr-txt-primary border-b border-gray-400 pb-1">{{ $c['website'] ?? 'www.odecci.com' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center {{ $mini ? 'gap-1' : 'gap-3' }}">
                            <x-icons.proposal.bulb class="{{ $fxIconSm }} clr-txt-primary shrink-0" />
                            <div class="flex flex-col border-b border-gray-400 pb-1 min-w-0">
                                <span
                                    class="{{ $fxBody }} clr-txt-primary">{{ $c['phone1'] ?? '+044 760 5422 – Sales Office' }}</span>
                                <span
                                    class="{{ $fxBody }} clr-txt-primary">{{ $c['phone2'] ?? '0961 645 8938 – Sales Office' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col {{ $mini ? 'gap-0.5' : 'gap-2' }}">
                            <p class="{{ $fxBody }} clr-txt-secondary">
                                {{ $c['social_label'] ?? 'Visit and follow us on:' }}</p>
                            <div class="flex {{ $mini ? 'gap-1' : 'gap-3' }}">
                                <x-icons.proposal.bulb class="{{ $fxIconSm }} clr-txt-primary" />
                                <x-icons.proposal.bulb class="{{ $fxIconSm }} clr-txt-primary" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @break

    {{-- ══════════════════════════════════════
         GENERIC LAYOUTS
    ══════════════════════════════════════ --}}
    @case('title')
        <div class="{{ $vpDark }} flex flex-col items-center justify-center text-center {{ $pad }}">
            @if (!empty($c['heading']))
                <h1 class="{{ $h1 }} font-bold text-white mb-2">{{ $c['heading'] }}</h1>
            @endif
            @if (!empty($c['subheading']))
                <p class="{{ $sub }} text-white/60">{{ $c['subheading'] }}</p>
            @endif
        </div>
    @break

    @case('content')
        <div class="{{ $vpDark }} flex flex-col {{ $pad }}">
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
        <div class="{{ $vpDark }} flex flex-col {{ $pad }}">
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
        <div class="{{ $vpDark }} flex flex-col items-center justify-center text-center {{ $pad }}">
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
