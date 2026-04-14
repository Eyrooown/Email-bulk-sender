<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
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

    public function pdf(Proposal $proposal): Response|RedirectResponse
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $slides = $proposal->slides()->orderBy('order')->get();
        $theme = $proposal->theme;
        $html = view('exports.proposal-pdf', compact('proposal', 'slides', 'theme'))->render();

        $a4lWidthPx = (int) round((297 / 25.4) * 96);
        $a4lHeightPx = (int) round((210 / 25.4) * 96);

        $node = config('services.browsershot.node_binary');
        $npm = config('services.browsershot.npm_binary');
        $chrome = config('services.browsershot.chrome_path');
        $nodeModules = config('services.browsershot.node_modules') ?: base_path('node_modules');

        $missingRequirements = [];
        if (is_string($node) && $node !== '' && !is_file($node)) {
            $missingRequirements[] = "Node binary not found at: {$node}";
        }
        if (is_string($npm) && $npm !== '' && !is_file($npm)) {
            $missingRequirements[] = "NPM binary not found at: {$npm}";
        }
        if (is_string($chrome) && $chrome !== '' && !is_file($chrome)) {
            $missingRequirements[] = "Chrome/Chromium binary not found at: {$chrome}";
        }
        if (!is_string($nodeModules) || !is_dir($nodeModules)) {
            $missingRequirements[] = "Node modules directory not found at: {$nodeModules}";
        }

        if (!empty($missingRequirements)) {
            return back()->with('error', 'PDF export requirements missing: '.implode(' | ', $missingRequirements));
        }

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

        if (is_string($nodeModules) && is_dir($nodeModules)) {
            $browsershot->setNodeModulePath($nodeModules);
        }

        if ($node) {
            $browsershot->setNodeBinary($node);
        }
        if ($npm) {
            $browsershot->setNpmBinary($npm);
        }
        if ($chrome) {
            $browsershot->setChromePath($chrome);
        }

        try {
            return response($browsershot->pdf(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.str($proposal->title)->slug().'.pdf"',
            ]);
        } catch (Throwable $e) {
            $rawMessage = trim($e->getMessage());
            $friendly = 'PDF export failed.';

            if (str_contains($rawMessage, 'Could not find Chrome')) {
                $friendly = 'PDF export failed: Chrome/Chromium is not installed or not reachable by Browsershot.';
            } elseif (str_contains($rawMessage, 'ENOENT') && str_contains($rawMessage, 'node')) {
                $friendly = 'PDF export failed: Node.js binary is missing or not reachable.';
            } elseif (str_contains($rawMessage, 'npm')) {
                $friendly = 'PDF export failed: npm is missing or not reachable.';
            }

            return back()->with('error', $friendly.' Details: '.$rawMessage);
        }
    }
}
