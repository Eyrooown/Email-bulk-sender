<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1122px">
    <title>{{ $proposal->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap");

        * {
            font-family: "Ubuntu", sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: white;
        }

        .page {
            width: 1122px;
            aspect-ratio: 1.414 / 1;
            overflow: hidden;
            page-break-after: always;
            page-break-inside: avoid;
            position: relative;
            background: white;
        }

        /* ── Brand colours ── */
        .clr-primary {
            background-color: #102b3c;
        }

        .clr-txt-primary {
            color: #102b3c;
        }

        .clr-text-primary {
            color: #102b3c;
        }

        .clr-txt-secondary {
            color: #205375;
        }

        .clr-bg-secondary {
            background-color: #205375;
        }

        .clr-bg-light {
            background-color: #d5d5d5;
        }

        .clr-txt-light {
            color: #f0efef;
        }

        .clr-accent {
            color: #ed1c24;
        }

        .clr-bg-accent {
            background-color: #ed1c24;
        }

        .border-clr-primary {
            border-color: #102b3c;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .page {
                page-break-after: always;
                page-break-inside: avoid;
                break-after: page;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div style="width:1122px; margin:0 auto;">
        <div class="flex flex-col w-full bg-gray-100">

            @foreach ($slides as $slide)
                @php
                    $c = $slide->content ?? [];
                    $layout = $slide->layout ?? 'blank';

                    if ($layout === 'fixed-executive') {
                        $c['body'] = !empty($c['body'])
                            ? $c['body']
                            : "Odecci Solutions Inc., is committed to delivering innovative, user-centric digital solutions that empower businesses to thrive in today's competitive landscape. This proposal outlines our comprehensive website development service designed to enhance your brand presence, improve customer engagement, and drive measurable business growth.\n\nOur approach combines cutting-edge technology, intuitive design, and strategic functionality to create a website that is not only visually appealing but also optimized for performance, security, and scalability. By leveraging modern frameworks and best practices, we ensure your website becomes a powerful tool for marketing, communication, and conversion.";
                        $c['bodyHighlights'] = !empty($c['bodyHighlights'])
                            ? $c['bodyHighlights']
                            : [
                                '**Custom Design & Branding:** Tailored to reflect your unique identity and values.',
                                '**Responsive & Mobile-First Development:** Seamless experience across all devices.',
                                '**SEO & Performance Optimization:** Enhanced visibility and faster load times.',
                                '**Content Management System (CMS):** Easy updates and scalability for future growth.',
                                '**Security & Compliance:** Robust measures to protect data and maintain trust.',
                                '**Analytics Integration:** Actionable insights to monitor and improve performance.',
                            ];
                        $c['bodyFooter'] = !empty($c['bodyFooter'])
                            ? $c['bodyFooter']
                            : 'Partnering with Odecci means gaining a strategic ally focused on delivering a website that aligns with your business objectives, strengthens your digital footprint, and creates lasting impact. Our team ensures timely delivery, transparent communication, and ongoing support to maximize your investment.';
                    }
                @endphp



                {{-- ══════════════════════════════════════════════
         FIXED-COVER  (Page 1 style)
    ══════════════════════════════════════════════ --}}
                @if ($layout === 'fixed-cover')
                    <div class="page flex w-full bg-white">
                        <div class="w-32 clr-primary shrink-0"></div>
                        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-row items-center gap-4">
                                    <x-logo />
                                    <div class="w-px h-12 clr-primary shrink-0"></div>
                                    <p class="text-lg clr-txt-secondary">
                                        {{ $c['tagline'] ?? 'Your Partner Towards Digital Innovation' }}</p>
                                </div>
                                <x-circles />
                            </div>
                            <div class="flex flex-row flex-1 gap-8">
                                <div class="w-px clr-primary shrink-0"></div>
                                <div class="flex flex-col justify-center gap-2">
                                    <p class="text-7xl font-bold clr-txt-primary">{{ $c['line1'] ?? 'WEBSITE' }}</p>
                                    <p class="text-7xl font-normal clr-txt-primary">{{ $c['line2'] ?? 'DEVELOPMENT' }}
                                    </p>
                                    <p class="text-7xl font-extralight clr-txt-secondary">
                                        {{ $c['line3'] ?? 'PROPOSAL' }}</p>
                                </div>
                                <div class="flex flex-1 justify-center items-center">
                                    <img src="{{ asset('images/icon-dark.png') }}" alt="Icon"
                                        class="h-3/4 w-auto" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-EXECUTIVE  (Page 2 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-executive')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">
                            <div class="flex justify-between items-center">
                                <p class="text-7xl clr-txt-primary">{{ $c['heading'] ?? 'Executive Summary' }}</p>
                                <x-circles />
                            </div>
                            <hr class="border-clr-primary w-2/5 border-2">
                            <div class="flex flex-row flex-1 gap-8">
                                <div class="flex flex-row w-full justify-center items-center gap-2 overflow-hidden">
                                    <div
                                        class="relative h-9/10 w-4/5 clr-txt-primary bg-white shadow-xl rounded-xl p-4 z-10">
                                        @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                            @if (trim($line) !== '')
                                                <p class="mb-3">{!! preg_replace('/\*\*(.*?)\*\*/', '<span class="font-bold">$1</span>', e(trim($line))) !!}</p>
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
                                                    <p class="mt-3">{!! preg_replace('/\*\*(.*?)\*\*/', '<span class="font-bold">$1</span>', e(trim($line))) !!}</p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="flex flex-col relative -ml-44 justify-end">
                                        <img src="{{ asset('images/executive-bg.png') }}" alt="Icon"
                                            class="h-3/4 w-auto" />
                                        <div
                                            class="absolute rounded-full h-96 w-96 clr-primary z-10 -bottom-40 -right-40">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-WHOIS  (Page 3 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-whois')
                    <div class="page flex w-full bg-white">
                        <div class="w-32 clr-primary shrink-0"></div>
                        <div class="flex flex-col w-full h-full px-12 py-6 gap-4">
                            <div class="flex justify-between items-center">
                                <div class="flex flex-row items-center gap-4"><x-logo /></div>
                                <div class="flex flex-col">
                                    <div class="flex flex-row justify-center items-center">
                                        <h1 class="text-6xl clr-txt-secondary mt-6">
                                            {{ $c['top_heading'] ?? 'OUR STRATEGY' }}</h1>
                                        <x-circles />
                                    </div>
                                    <hr class="w-3/4 border-2 border-clr-primary mt-4">
                                </div>
                            </div>
                            <div class="flex flex-row justify-between flex-1 gap-2">
                                <div class="flex flex-col flex-1 justify-center gap-4">
                                    @php $heading = $c['heading'] ?? null; @endphp
                                    <h1 class="font-medium text-6xl clr-txt-primary">
                                        @if ($heading)
                                            {!! nl2br(e(str_replace('\n', "\n", $heading))) !!}
                                        @else
                                            Who is<br>Odecci?
                                        @endif
                                    </h1>
                                    <div class="clr-txt-primary text-sm leading-relaxed space-y-2">
                                        @foreach (explode("\n", (string) ($c['body'] ?? '')) as $line)
                                            @if (trim($line) !== '')
                                                <p>{{ trim($line) }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                    @php
                                        $website = $c['website'] ?? 'https://odecci.com';
                                    @endphp
                                    @if (!empty($website))
                                        <p class="text-sm">Visit our website:
                                            <a href="https://{{ ltrim($website, 'https://') }}"
                                                class="underline decoration-solid">{{ $website }}</a> to learn
                                            more
                                        </p>
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-row justify-center items-center">
                                    <div class="grid grid-cols-2 gap-8 w-full">
                                        @foreach ($c['bullets'] ?? [] as $bullet)
                                            @php
                                                $bulletText = is_array($bullet) ? $bullet['text'] ?? '' : $bullet;
                                                $bulletIcon = is_array($bullet)
                                                    ? $bullet['icon'] ?? 'diamond'
                                                    : 'diamond';
                                            @endphp
                                            <div class="flex flex-row items-center gap-4">
                                                <div
                                                    class="flex justify-center items-center h-16 w-16 shrink-0 rounded-full clr-bg-secondary text-white">
                                                    @switch($bulletIcon)
                                                        @case('paperplane')
                                                            <x-icons.proposal.paperplane class="w-6 h-6" />
                                                        @break

                                                        @case('chart')
                                                            <x-icons.proposal.chart class="w-6 h-6" />
                                                        @break

                                                        @case('calendar-check')
                                                            <x-icons.proposal.calendar-check class="w-6 h-6" />
                                                        @break

                                                        @case('bulb')
                                                            <x-icons.proposal.bulb class="w-6 h-6" />
                                                        @break

                                                        @default
                                                            <x-icons.proposal.diamond class="w-6 h-6" />
                                                    @endswitch
                                                </div>
                                                <p class="clr-txt-secondary font-bold text-base">{{ $bulletText }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-STRATEGY-CARDS  (Page 4 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-strategy-cards')
                    @php
                        $strategyDefaults = [
                            1 => [
                                'title' => 'Hand Tailored Solutions',
                                'body' =>
                                    "Design websites that are uniquely customized to align with each client's specific business needs, from branded interfaces to intricate technical functionalities, ensuring a perfect fit for their operations.",
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
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-col w-full h-full px-12 py-6 gap-6">
                            <div class="flex justify-between items-center shrink-0">
                                <div class="flex flex-row items-center justify-between gap-20">
                                    <div class="flex flex-col gap-4">
                                        <h1 class="text-6xl font-bold clr-txt-primary">
                                            {{ $c['heading'] ?? 'Our Strategy' }}</h1>
                                        <hr class="w-3/4 border border-clr-primary">
                                    </div>
                                    <p class="text-lg clr-txt-secondary">{!! nl2br(e($c['subheading'] ?? "We understand that every business has\nunique goals for its system, such as:")) !!}</p>
                                </div>
                                <x-circles />
                            </div>
                            <div class="grid grid-cols-5 gap-4 w-full items-stretch h-3/5 mt-5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $isDark = $i % 2 === 1;
                                        $cardTitle = trim(
                                            (string) ($c["card{$i}_title"] ?? $strategyDefaults[$i]['title']),
                                        );
                                        $titleWords = preg_split('/\s+/', $cardTitle) ?: [];
                                        $iconFromTitle = \Illuminate\Support\Str::slug(
                                            implode(' ', array_slice($titleWords, 0, 2)),
                                        );
                                        $iconName = file_exists(
                                            resource_path("views/components/icons/proposal/{$iconFromTitle}.blade.php"),
                                        )
                                            ? $iconFromTitle
                                            : 'diamond';
                                    @endphp
                                    <div
                                        class="flex flex-col min-w-0 {{ $isDark ? 'clr-primary text-white' : 'bg-white clr-txt-primary shadow-md' }} rounded-2xl p-6 w-full h-full">
                                        <div class="flex flex-col items-center gap-3">
                                            <x-dynamic-component :component="'icons.proposal.' . $iconName" :classes="'w-12 h-12 mb-1'" />
                                            <hr
                                                class="w-full border {{ $isDark ? 'border-white' : 'border-clr-primary' }}">
                                            <h1 class="text-base font-bold text-center w-full">
                                                {{ $c["card{$i}_title"] ?? $strategyDefaults[$i]['title'] }}</h1>
                                            <p class="text-center text-xs leading-snug">
                                                {{ $c["card{$i}_body"] ?? $strategyDefaults[$i]['body'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-PROBLEM-STATEMENT  (Page 5 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-problem-statement')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-col w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-7xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Problem Statement' }}</h1>
                                    <hr class="w-2/5 border-t border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <p class="text-lg leading-relaxed clr-txt-primary max-w-5xl mt-4 shrink-0">
                                {{ $c['body'] ?? '' }}</p>
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
                            <div class="flex flex-col w-full max-w-6xl mx-auto gap-10 mt-4 flex-1">
                                <div class="flex items-center gap-3 w-full mb-3">
                                    <div class="flex-1 border-t border-dashed border-gray-400"></div>
                                    <div
                                        class="clr-primary text-white p-4 rounded-full font-bold text-xl tracking-widest uppercase whitespace-nowrap">
                                        {{ $c['pill'] ?? 'Top 5 most common problems encountered' }}
                                    </div>
                                    <div class="flex-1 border-t border-dashed border-gray-400"></div>
                                </div>
                                <div class="grid grid-cols-5 gap-2 w-full">
                                    @foreach ($problemItems as $n => $label)
                                        <div
                                            class="bg-white rounded-xl flex flex-col justify-between text-center shadow-md h-40 overflow-visible">
                                            <p class="text-sm font-bold clr-txt-primary leading-tight px-3 pt-4">
                                                {{ $label }}</p>
                                            <div class="relative w-full h-8 mt-2">
                                                <div
                                                    class="absolute w-full inset-x-0 bottom-0 h-4 {{ ($n + 1) % 2 === 1 ? 'clr-primary' : 'clr-bg-secondary' }}
                                    {{ $n === 0 ? 'rounded-bl-xl' : '' }}
                                    {{ $n === 4 ? 'rounded-br-xl' : '' }}">
                                                </div>
                                                <div class="absolute inset-x-0 top-0 flex justify-center">
                                                    <span
                                                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md border border-gray-200 text-sm font-bold clr-txt-primary">
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

                    {{-- ══════════════════════════════════════════════
         FIXED-CUSTOM-SOLUTION  (Page 6 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-custom-solution')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-1 flex-col justify-between w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-7xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Our Custom Solution' }}</h1>
                                    <hr class="w-2/5 border-t border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <p class="text-lg leading-relaxed clr-txt-secondary max-w-5xl shrink-0">
                                {{ $c['body'] ?? '' }}</p>
                            @php
                                $solutionItems = [];
                                $boxClasses = [
                                    'clr-primary',
                                    'clr-bg-secondary',
                                    'clr-bg-light',
                                    'clr-bg-secondary',
                                    'clr-primary',
                                ];
                                $iconClasses = [
                                    'text-white',
                                    'text-white',
                                    'clr-txt-primary',
                                    'text-white',
                                    'text-white',
                                ];
                                for ($i = 1; $i <= 5; $i++) {
                                    $solutionItems[] = [
                                        'boxClass' => $boxClasses[$i - 1],
                                        'iconClass' => $iconClasses[$i - 1],
                                        'title' => $c["solution{$i}_title"] ?? '',
                                        'desc' => $c["solution{$i}_desc"] ?? '',
                                    ];
                                }
                                $solutionItems = array_filter($solutionItems, fn($s) => !empty($s['title']));
                                if (empty($solutionItems)) {
                                    $solutionItems = [
                                        [
                                            'boxClass' => 'clr-primary',
                                            'iconClass' => 'text-white',
                                            'title' => 'Seamless User Experience',
                                            'desc' =>
                                                'Intuitive navigation and mobile-first design for maximum engagement.',
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
                                            'desc' =>
                                                'Integrated SEO strategies to boost search rankings and attract organic traffic.',
                                        ],
                                        [
                                            'boxClass' => 'clr-bg-secondary',
                                            'iconClass' => 'text-white',
                                            'title' => 'Enterprise-Level Security',
                                            'desc' =>
                                                'Strong protection against cyber threats and compliance with data standards.',
                                        ],
                                        [
                                            'boxClass' => 'clr-primary',
                                            'iconClass' => 'text-white',
                                            'title' => 'Actionable Insights',
                                            'desc' =>
                                                'Analytics integration for informed decision-making and continuous improvement.',
                                        ],
                                    ];
                                }
                            @endphp
                            <div class="grid grid-cols-5 gap-4 w-full mt-2 h-3/5 items-start">
                                @foreach ($solutionItems as $item)
                                    @php
                                        $solutionTitle = trim((string) ($item['title'] ?? ''));
                                        $titleWords = preg_split('/[\s-]+/', $solutionTitle) ?: [];
                                        $iconFromTitle = \Illuminate\Support\Str::slug($titleWords[0] ?? '');
                                        $iconName = $iconFromTitle !== '' &&
                                            file_exists(
                                                resource_path("views/components/icons/proposal/{$iconFromTitle}.blade.php"),
                                            )
                                            ? $iconFromTitle
                                            : 'bulb';
                                    @endphp
                                    <div class="flex flex-col items-center gap-0 h-full">
                                        <div
                                            class="{{ $item['boxClass'] }} text-white rounded-xl flex items-center justify-center px-4 py-6 w-full">
                                            <x-dynamic-component :component="'icons.proposal.' . $iconName"
                                                :classes="'w-14 h-14 shrink-0 ' . $item['iconClass']" />
                                        </div>
                                        <div class="w-px h-6 border-l border-dashed border-gray-400"></div>
                                        <div class="flex flex-col gap-1 h-2/5">
                                            <h2 class="text-lg font-bold clr-txt-primary text-center">
                                                {{ $item['title'] }}</h2>
                                            <p class="text-sm clr-txt-secondary text-center leading-snug px-0.5">
                                                {{ $item['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-SCOPE-BASIC / BUSINESS / STORE  (Pages 7–9 style)
         These reuse your existing x-scope-card component.
         The content is still loaded from the slide's stored data.
    ══════════════════════════════════════════════ --}}
                @elseif (in_array($layout, ['fixed-scope-basic', 'fixed-scope-business', 'fixed-scope-store']))
                    @php
                        // Decode tags
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

                        // Defaults per package if editor fields are empty
                        if ($layout === 'fixed-scope-basic' && empty($whatYouGet)) {
                            $c['packageName'] = $c['packageName'] ?? 'Basic Website Package';
                            $c['idealFor'] =
                                $c['idealFor'] ??
                                'Small to Medium Businesses looking for a professional online presence without complexity.';
                            $c['revision'] = $c['revision'] ?? '1 Design Revision';
                            $c['benefit'] = $c['benefit'] ?? "What You'll Get";
                            $tags = ['Tailored Design', '2 Weeks Delivery'];
                            $whatYouGet = [
                                ['title' => '1-3', 'desc' => 'Mobile Friendly Design'],
                                [
                                    'title' => 'Content Management System (CMS)',
                                    'desc' => 'Effortless content updates and scalability.',
                                ],
                                [
                                    'title' => 'Basic SEO Setup',
                                    'desc' => 'Improve search visibility and attract organic traffic.',
                                ],
                                [
                                    'title' => 'Google Analytics Integration',
                                    'desc' => 'Track performance and user behavior for data-driven decisions.',
                                ],
                            ];
                            $inclusions = [
                                [
                                    'title' => '1 Month Free Support',
                                    'desc' => 'Post-launch assistance for smooth operations.',
                                ],
                                [
                                    'title' => 'Google Analytics Setup',
                                    'desc' => 'Track visitor behavior and performance.',
                                ],
                                [
                                    'title' => 'Google Business Profile Setup',
                                    'desc' => 'Enhance your local search presence.',
                                ],
                                [
                                    'title' => '3 Personalized Business Email Addresses',
                                    'desc' => 'Professional communication for your team.',
                                ],
                                ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                                [
                                    'title' => 'Free Domain (.com) with SSL for 1 Year',
                                    'desc' => 'Secure and professional online identity.',
                                ],
                                [
                                    'title' => 'Free CMS Training',
                                    'desc' => '1-day, 2-hour live session to empower your team.',
                                ],
                            ];
                        }
                        if ($layout === 'fixed-scope-business' && empty($whatYouGet)) {
                            $c['packageName'] = $c['packageName'] ?? 'Business Website Package';
                            $c['idealFor'] =
                                $c['idealFor'] ??
                                'Scaling businesses that want their brand to stand out, increase traffic, and showcase products and services with a professional, feature-rich website.';
                            $c['revision'] = $c['revision'] ?? '2 Design Revision';
                            $c['benefit'] = $c['benefit'] ?? "You'll Get All from basic plus:";
                            $tags = ['Tailored Design', '4 Weeks Delivery', 'CRM Integration'];
                            $whatYouGet = [
                                ['title' => '3-6', 'desc' => 'Mobile-Friendly Pages'],
                                [
                                    'title' => 'Products/Services Page',
                                    'desc' => 'Highlight your offerings with engaging layouts.',
                                ],
                                ['title' => 'Blog & News Page', 'desc' => 'Share updates, insights, and boost SEO.'],
                                [
                                    'title' => 'Content Management System (CMS)',
                                    'desc' => 'Easy content updates and scalability.',
                                ],
                                [
                                    'title' => 'CRM Integration (HubSpot)',
                                    'desc' => 'Streamline customer relationship management.',
                                ],
                                [
                                    'title' => 'Advanced SEO Setup',
                                    'desc' => 'Drive organic traffic and improve search rankings.',
                                ],
                            ];
                            $inclusions = [
                                [
                                    'title' => '2 Month Free Support',
                                    'desc' => 'Extended assistance for smooth operations.',
                                ],
                                [
                                    'title' => 'Google Analytics Setup',
                                    'desc' => 'Monitor performance and user behavior.',
                                ],
                                [
                                    'title' => 'Google Business Profile Setup',
                                    'desc' => 'Strengthen your local presence.',
                                ],
                                [
                                    'title' => '5 Personalized Business Email Addresses',
                                    'desc' => 'Professional communication for your team.',
                                ],
                                ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                                [
                                    'title' => 'Free Domain (.com) with SSL for 1 Year',
                                    'desc' => 'Secure and professional online identity.',
                                ],
                                [
                                    'title' => 'Free CMS Training',
                                    'desc' => '2-days with 2-hour live session to empower your team.',
                                ],
                            ];
                        }
                        if ($layout === 'fixed-scope-store' && empty($whatYouGet)) {
                            $c['packageName'] = $c['packageName'] ?? 'Online Store Website Package';
                            $c['idealFor'] =
                                $c['idealFor'] ??
                                'Retailers, wholesalers, and brands that want to sell online with ease.';
                            $c['revision'] = $c['revision'] ?? '3 Design Revision';
                            $c['benefit'] = $c['benefit'] ?? "You'll Get All from basic and business plus:";
                            $tags = ['Tailored Design', '8 Weeks Delivery', 'CRM Integration', 'Payment Integration'];
                            $whatYouGet = [
                                [
                                    'title' => 'Unlimited Mobile-Friendly Design',
                                    'desc' =>
                                        'Fully responsive layouts for an exceptional user experience across all devices.',
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
                                [
                                    'title' => 'Online Payment Integration',
                                    'desc' => 'Accept payments securely and conveniently.',
                                ],
                                [
                                    'title' => 'Customer Dashboard',
                                    'desc' => 'Empower customers with account management tools.',
                                ],
                            ];
                            $inclusions = [
                                [
                                    'title' => '3 Month Free Support',
                                    'desc' => 'Extended assistance for smooth operations.',
                                ],
                                [
                                    'title' => 'Google Analytics Setup',
                                    'desc' => 'Track visitor behavior and performance.',
                                ],
                                [
                                    'title' => 'Google Business Profile Setup',
                                    'desc' => 'Enhance your local search presence.',
                                ],
                                [
                                    'title' => '10 Personalized Business Email Addresses',
                                    'desc' => 'Professional communication for your team.',
                                ],
                                ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                                [
                                    'title' => 'Free Domain (.com) with SSL for 1 Year',
                                    'desc' => 'Secure and professional online identity.',
                                ],
                                [
                                    'title' => 'Free CMS Training',
                                    'desc' => '3-days, 2-hour live session to empower your team.',
                                ],
                            ];
                        }
                    @endphp
                    <div class="page flex w-full bg-white">
                        <x-scope-card :packageName="$c['packageName'] ?? 'Package'" :idealFor="$c['idealFor'] ?? ''" :revision="$c['revision'] ?? ''" :tags="$tags"
                            :benefit="$c['benefit'] ?? 'What You\'ll Get'" :whatYouGet="$whatYouGet" :inclusions="$inclusions" />
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-TERMS  (Page 10 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-terms')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-1 flex-col w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-5xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Terms And Condition' }}</h1>
                                    <hr class="w-2/5 border-t border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <div class="flex flex-col gap-4 mt-1">
                                <div class="flex flex-col gap-2">
                                    <p class="text-sm font-bold clr-txt-primary">Payment Terms</p>
                                    <table class="w-full text-sm border-collapse">
                                        <thead>
                                            <tr class="clr-primary text-white">
                                                <th class="text-left px-6 py-3 w-1/4 font-bold italic">Percentage</th>
                                                <th class="text-left px-6 py-3 font-bold italic">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-gray-100">
                                                <td class="px-6 py-3 italic clr-txt-primary text-sm">
                                                    {{ $c['payment_row1_pct'] ?? '50% Downpayment' }}</td>
                                                <td class="px-6 py-3 italic clr-txt-secondary text-sm">
                                                    {{ $c['payment_row1_desc'] ?? 'Upon contract signing' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-3 italic clr-txt-primary text-sm">
                                                    {{ $c['payment_row2_pct'] ?? '50% Deployment' }}</td>
                                                <td class="px-6 py-3 italic clr-txt-secondary text-sm">
                                                    {{ $c['payment_row2_desc'] ?? 'Upon turnover and project acceptance.' }}
                                                </td>
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
                                        <div class="flex flex-col gap-1.5">
                                            <p class="text-sm font-bold clr-txt-primary">{{ $section['label'] }}</p>
                                            <ul class="flex flex-col gap-1">
                                                @foreach ($bullets as $bullet)
                                                    <li class="text-sm clr-txt-secondary">• {{ $bullet }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-bold clr-txt-primary">Execution of Service Level Agreement:
                                    </p>
                                    <p class="text-sm clr-txt-secondary leading-relaxed">
                                        {{ $c['sla_text'] ?? 'Upon approval of this proposal, a detailed Service Level Agreement (SLA) will be executed to formalize the terms of service, ensuring clarity on the delivery, management, and support of the website throughout the contract period.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-PROJECTS  (Page 11 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-projects')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-1 flex-col w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-5xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Some of our Website Projects' }}</h1>
                                    <hr class="w-3/5 border-2 border-t mt-4 border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <div class="grid grid-cols-2 grid-rows-2 gap-4 mt-1">
                                <a href="{{ $c['project1_url'] ?? 'https://htms.com.ph/' }}" target="_blank"
                                    class="w-full h-full">
                                    <img src="{{ asset('images/htms.png') }}"
                                        alt="{{ $c['project1_label'] ?? 'HTMS Website' }}"
                                        class="w-full h-auto rounded-lg shadow-md">
                                </a>
                                <a href="{{ $c['project2_url'] ?? 'https://yskprojdev.ph/' }}" target="_blank"
                                    class="w-full h-full">
                                    <img src="{{ asset('images/ysk.png') }}"
                                        alt="{{ $c['project2_label'] ?? 'Project 2' }}"
                                        class="w-full h-auto rounded-lg shadow-md">
                                </a>
                                <a href="{{ $c['project3_url'] ?? 'https://srresidencesalmouj.com/' }}"
                                    target="_blank" class="w-full h-full">
                                    <img src="{{ asset('images/st-regis.png') }}"
                                        alt="{{ $c['project3_label'] ?? 'Project 3' }}"
                                        class="w-full h-auto rounded-lg shadow-md">
                                </a>
                                <a href="{{ $c['portfolio_url'] ?? 'https://odecci.com/odecci-portfolio/' }}"
                                    target="_blank"
                                    class="flex items-center justify-center w-full h-full clr-primary text-white underline font-bold rounded-lg shadow-md">
                                    {{ $c['portfolio_label'] ?? 'View Our Portfolio >>' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-ORGANIZATIONS  (Page 12 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-organizations')
                    @php
                        $organizations =
                            $c['organizations'] ??
                            array_map(
                                fn($i) => ['name' => "Organization {$i}", 'image' => "images/organization{$i}.png"],
                                range(1, 12),
                            );
                    @endphp
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-1 flex-col w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-5xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Organizations we work with' }}</h1>
                                    <hr class="w-3/5 border-2 border-t mt-4 border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <div class="grid grid-cols-3 grid-rows-4 gap-8 mt-4">
                                @foreach ($organizations as $org)
                                    <div class="flex justify-center items-center">
                                        <img src="{{ asset($org['image']) }}" alt="{{ $org['name'] }}"
                                            class="w-32 h-32 object-contain">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-TESTIMONIAL  (Page 13 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-testimonial')
                    <div class="page flex w-full bg-white">
                        <div class="flex flex-1 flex-col w-full h-full px-10 py-5 gap-3">
                            <div class="flex justify-between items-start shrink-0 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <h1 class="text-5xl font-bold clr-txt-primary tracking-tight">
                                        {{ $c['heading'] ?? 'Testimonial' }}</h1>
                                    <hr class="w-3/5 border-2 border-t mt-4 border-clr-primary">
                                </div>
                                <x-circles />
                            </div>
                            <div class="flex flex-row h-3/5 justify-center items-center">
                                <div class="-space-x-12 flex flex-row justify-center items-center h-full w-3/5 mt-4">
                                    <div
                                        class="clr-primary rounded-3xl w-full h-full flex items-center justify-center p-6">
                                        <p class="text-gray-300 text-center text-sm">
                                            {{ $c['testimonial1'] ?? 'Testimonial 1' }}</p>
                                    </div>
                                    <div
                                        class="clr-bg-light rounded-3xl w-full h-full flex items-center justify-center p-6">
                                        <p class="text-gray-500 text-center text-sm">
                                            {{ $c['testimonial2'] ?? 'Testimonial 2' }}</p>
                                    </div>
                                    <div
                                        class="clr-primary rounded-3xl w-full h-full flex items-center justify-center p-6">
                                        <p class="text-gray-300 text-center text-sm">
                                            {{ $c['testimonial3'] ?? 'Testimonial 3' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-WHY-CUSTOMIZE  (Page 14 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-why-customize')
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
                    <div class="page flex w-full bg-white">
                        <div class="flex w-full h-full">
                            <div class="flex flex-col w-1/2 px-10 py-5 justify-between">
                                <div class="flex flex-col gap-4">
                                    <x-circles />
                                    <h1 class="text-5xl font-bold clr-txt-primary tracking-tight leading-tight">
                                        {{ $c['heading'] ?? 'Why Your Business Needs a Customized Application' }}</h1>
                                    <p class="text-lg clr-txt-secondary leading-relaxed">
                                        {{ $c['body'] ?? 'Every business is unique, even if daily operations seem similar across industries...' }}
                                    </p>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <hr class="w-10 border-t-2 border-clr-primary">
                                    <p class="text-lg clr-txt-secondary leading-relaxed">
                                        {{ $c['footer'] ?? "At Odecci, we don't just build systems, we create platforms that work as smart, strategic tools for your business." }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex flex-col justify-center items-center w-1/2 clr-primary h-full px-8 py-6 gap-6">
                                <div class="grid grid-cols-2 justify-center items-center gap-x-6 gap-y-5 w-full">
                                    @foreach ($whyItems as $index => $item)
                                        <div class="flex flex-col gap-2 {{ $index === 4 ? 'col-span-2 w-1/2' : '' }}">
                                            <div class="relative inline-block">
                                                <div
                                                    class="{{ $item['titleBg'] ?? 'bg-white' }} {{ $item['titleText'] ?? 'clr-txt-primary' }} px-4 py-2.5 rounded-lg text-xs font-bold leading-snug">
                                                    {{ $item['title'] }}
                                                </div>
                                                <div
                                                    class="absolute left-5 -bottom-2 w-0 h-0 border-l-[8px] border-l-transparent border-r-[8px] border-r-transparent border-t-[10px] {{ in_array($index, [0, 3]) ? 'border-t-[#2d4a6b]' : 'border-t-white' }}">
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-300 leading-relaxed mt-2">{{ $item['desc'] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-GUIDANCE  (Page 15 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-guidance')
                    <div class="page flex flex-col items-center w-full bg-white">
                        <div class="w-full h-full px-10 flex flex-row items-start pt-8">
                            <div class="w-1/2 h-4/5 rounded-2xl overflow-hidden shrink-0">
                                <img src="{{ asset('images/guidance.png') }}" alt="Guidance"
                                    class="w-full h-full object-cover">
                            </div>
                            <div
                                class="clr-primary rounded-3xl px-10 py-10 flex flex-col items-center justify-center text-center gap-4 h-2/5 w-5/12 self-start -ml-16 mt-8">
                                <h1 class="text-5xl font-light text-white tracking-tight">
                                    {{ $c['heading'] ?? 'Need guidance?' }}</h1>
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm text-gray-300">
                                        {{ $c['subtext1'] ?? "We're here to help. Contact us for a free consultation." }}
                                    </p>
                                    <p class="text-sm text-gray-300">
                                        {{ $c['subtext2'] ?? 'Or click this link and book now:' }}</p>
                                </div>
                                <a href="{{ $c['link'] ?? 'https://odecci.com/consultation/' }}" target="_blank"
                                    class="text-sm text-blue-400 underline">
                                    {{ $c['link_label'] ?? ($c['link'] ?? 'https://odecci.com/consultation/') }}
                                </a>
                            </div>
                        </div>
                        <div class="mt-auto flex justify-end w-full pb-4 pr-6">
                            <x-circles />
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         FIXED-CONTACT  (Page 16 style)
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'fixed-contact')
                    <div class="page flex w-full bg-white">
                        <div class="flex w-full h-full">
                            <div class="clr-primary w-32 shrink-0 h-full"></div>
                            <div class="flex flex-col w-full h-full px-10 py-6 justify-between">
                                <div class="flex justify-between items-start w-full">
                                    <x-logo />
                                </div>
                                <div class="flex row justify-between items-center w-full gap-4">
                                    <div class="flex flex-col leading-none">
                                        <span
                                            class="text-7xl font-bold clr-txt-primary tracking-tight">{{ $c['line1'] ?? 'CONTACT' }}</span>
                                        <span
                                            class="text-7xl font-light text-gray-300 tracking-tight">{{ $c['line2'] ?? 'US NOW' }}</span>
                                    </div>
                                    <img src="{{ asset('images/icon-dark.png') }}" alt="Logo"
                                        class="w-1/3 h-auto object-contain">
                                </div>
                                <div class="flex flex-row items-start gap-16 pb-2">
                                    <div class="flex flex-col gap-3">
                                        <div class="flex items-center gap-3">
                                            <x-icons.proposal.bulb class="w-5 h-5 clr-txt-primary shrink-0" />
                                            <div class="flex flex-col border-b border-gray-400 pb-1 min-w-[180px]">
                                                <a href="mailto:{{ $c['email1'] ?? 'info@odecci.com' }}"
                                                    class="text-xs clr-txt-primary">{{ $c['email1'] ?? 'info@odecci.com' }}</a>
                                                <a href="mailto:{{ $c['email2'] ?? 'sales@odecci.com' }}"
                                                    class="text-xs clr-txt-primary">{{ $c['email2'] ?? 'sales@odecci.com' }}</a>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <x-icons.proposal.bulb class="w-5 h-5 clr-txt-primary shrink-0" />
                                            <div class="border-b border-gray-400 pb-1 min-w-[180px]">
                                                <a href="https://{{ ltrim($c['website'] ?? 'www.odecci.com', 'https://') }}"
                                                    target="_blank"
                                                    class="text-xs clr-txt-primary">{{ $c['website'] ?? 'www.odecci.com' }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-icons.proposal.bulb class="w-5 h-5 clr-txt-primary shrink-0" />
                                        <div class="flex flex-col border-b border-gray-400 pb-1 min-w-[200px]">
                                            <span
                                                class="text-xs clr-txt-primary">{{ $c['phone1'] ?? '+044 760 5422 – Sales Office' }}</span>
                                            <span
                                                class="text-xs clr-txt-primary">{{ $c['phone2'] ?? '0961 645 8938 – Sales Office' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <p class="text-xs clr-txt-secondary">
                                            {{ $c['social_label'] ?? 'Visit and follow us on:' }}</p>
                                        <div class="flex gap-3">
                                            <x-icons.proposal.bulb class="w-5 h-5 clr-txt-primary" />
                                            <x-icons.proposal.bulb class="w-5 h-5 clr-txt-primary" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════════
         GENERIC LAYOUTS (title / content / two-col / quote)
         These use the dark theme — kept for flexibility.
    ══════════════════════════════════════════════ --}}
                @elseif ($layout === 'title')
                    <div class="page flex items-center justify-center bg-gray-900 text-white text-center px-16">
                        @if (!empty($c['heading']))
                            <div>
                                <h1 class="text-7xl font-bold mb-4">{{ $c['heading'] }}</h1>
                                @if (!empty($c['subheading']))
                                    <p class="text-2xl text-white/60">{{ $c['subheading'] }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @elseif ($layout === 'content')
                    <div class="page flex flex-col bg-gray-900 text-white px-16 py-12">
                        @if (!empty($c['heading']))
                            <h2 class="text-5xl font-bold mb-6">{{ $c['heading'] }}</h2>
                        @endif
                        @if (!empty($c['body']))
                            <div class="text-lg text-white/75 space-y-2">
                                @foreach (explode("\n", $c['body']) as $line)
                                    @if (trim($line))
                                        <p>{{ trim($line) }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif ($layout === 'two-col')
                    <div class="page flex flex-col bg-gray-900 text-white px-16 py-12">
                        @if (!empty($c['heading']))
                            <h2 class="text-5xl font-bold mb-6">{{ $c['heading'] }}</h2>
                        @endif
                        <div class="flex-1 grid grid-cols-2 gap-8">
                            <div class="text-base text-white/75 space-y-1 border-r border-white/10 pr-8">
                                @foreach (explode("\n", $c['col1'] ?? '') as $line)
                                    @if (trim($line))
                                        <p>{{ trim($line) }}</p>
                                    @endif
                                @endforeach
                            </div>
                            <div class="text-base text-white/75 space-y-1">
                                @foreach (explode("\n", $c['col2'] ?? '') as $line)
                                    @if (trim($line))
                                        <p>{{ trim($line) }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif ($layout === 'quote')
                    <div class="page flex items-center justify-center bg-gray-900 text-white text-center px-20">
                        <div>
                            @if (!empty($c['quote']))
                                <blockquote class="text-4xl italic text-white/90 max-w-3xl mx-auto">
                                    {{ $c['quote'] }}</blockquote>
                            @endif
                            @if (!empty($c['author']))
                                <p class="text-sm text-white/50 mt-6 tracking-widest uppercase">{{ $c['author'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Blank / unknown layout --}}
                    <div class="page flex w-full bg-white"></div>
                @endif

            @endforeach

        </div>
    </div>
</body>

</html>
