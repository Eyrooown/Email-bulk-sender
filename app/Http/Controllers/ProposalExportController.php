<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Dompdf\Dompdf;
use Illuminate\Http\Response;
use Spatie\Browsershot\Browsershot;
use Throwable;

class ProposalExportController extends Controller
{
    public function preview(Proposal $proposal)
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $slides = $proposal->slides()->orderBy('order')->get();
        $theme = $proposal->theme;

        return view('exports.proposal-pdf', compact('proposal', 'slides', 'theme'));
    }

    public function pdf(Proposal $proposal): Response
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $slides = $proposal->slides()->orderBy('order')->get();
        $theme = $proposal->theme;
        $html = view('exports.proposal-pdf', compact('proposal', 'slides', 'theme'))->render();

        if (class_exists(Browsershot::class)) {
            try {
                $a4lWidthPx = (int) round((297 / 25.4) * 96);
                $a4lHeightPx = (int) round((210 / 25.4) * 96);

                $browsershot = Browsershot::html($html)
                    ->timeout(120)
                    ->waitUntilNetworkIdle(false)
                    ->delay(500)
                    ->showBackground()
                    ->emulateMedia('print')
                    ->windowSize($a4lWidthPx, $a4lHeightPx)
                    ->deviceScaleFactor(2)
                    ->margins(0, 0, 0, 0)
                    ->noSandbox()
                    ->scale(1)
                    ->setOption('preferCSSPageSize', true);

                $nodeModules = config('services.browsershot.node_modules') ?: base_path('node_modules');
                if (is_string($nodeModules) && is_dir($nodeModules)) {
                    $browsershot->setNodeModulePath($nodeModules);
                }

                if ($node = config('services.browsershot.node_binary')) {
                    $browsershot->setNodeBinary($node);
                }
                if ($npm = config('services.browsershot.npm_binary')) {
                    $browsershot->setNpmBinary($npm);
                }
                if ($chrome = config('services.browsershot.chrome_path')) {
                    $browsershot->setChromePath($chrome);
                }

                return response($browsershot->pdf(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="'.str($proposal->title)->slug().'.pdf"',
                ]);
            } catch (Throwable $e) {
                // If Browsershot fails in production (missing node/chrome), fall back to Dompdf.
            }
        }

        $dompdf = new Dompdf;
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.str($proposal->title)->slug().'.pdf"',
        ]);
    }
}
