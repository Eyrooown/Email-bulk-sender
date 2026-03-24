<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $proposal->title }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        @page { size: 297mm 210mm landscape; margin: 0; }
        html, body { width: 297mm; margin: 0 auto; font-family: DejaVu Sans, sans-serif; }
        .slide-page { width: 297mm; height: 210mm; page-break-after: always; position: relative; overflow: hidden; }
        .slide-inner { position: absolute; inset: 0; padding: 24mm; }
        .theme-midnight { background: #111827; color: #fff; }
        .theme-aurora { background: #1e1b4b; color: #fff; }
        .theme-slate { background: #334155; color: #fff; }
        .theme-rose { background: #4c0519; color: #fff; }
        .theme-forest { background: #022c22; color: #fff; }
    </style>
</head>
<body>
@foreach ($slides as $slide)
    @php
        $c = $slide->content ?? [];
        $layout = $slide->layout ?? 'blank';
        $themeClass = 'theme-' . ($theme ?? 'midnight');
    @endphp

    <div class="slide-page {{ $themeClass }}">
        <div class="slide-inner">
            @if ($layout === 'title')
                <h1 style="font-size: 40pt; margin-top: 40mm;">{{ $c['heading'] ?? '' }}</h1>
                <p style="font-size: 16pt; margin-top: 8pt; opacity: .8;">{{ $c['subheading'] ?? '' }}</p>
            @elseif ($layout === 'content')
                <h2 style="font-size: 28pt; margin-bottom: 10pt;">{{ $c['heading'] ?? '' }}</h2>
                <p style="font-size: 13pt; line-height: 1.6; white-space: pre-line;">{{ $c['body'] ?? '' }}</p>
            @elseif ($layout === 'two-col')
                <h2 style="font-size: 28pt; margin-bottom: 10pt;">{{ $c['heading'] ?? '' }}</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 10mm; border-right: 1px solid rgba(255,255,255,0.3);">{{ $c['col1'] ?? '' }}</td>
                        <td style="width: 50%; vertical-align: top; padding-left: 10mm;">{{ $c['col2'] ?? '' }}</td>
                    </tr>
                </table>
            @elseif ($layout === 'quote')
                <p style="font-size: 24pt; font-style: italic; margin-top: 45mm;">{{ $c['quote'] ?? '' }}</p>
                <p style="font-size: 11pt; margin-top: 12pt; opacity: .7;">{{ $c['author'] ?? '' }}</p>
            @endif
        </div>
    </div>
@endforeach
</body>
</html>
