<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;

class ProposalController extends Controller
{
    public function index(): View
    {
        $proposals = Proposal::query()
            ->where('user_id', auth()->id())
            ->withCount('slides')
            ->latest()
            ->get();

        return view('proposals.index', compact('proposals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $proposal = Proposal::create([
            'user_id' => auth()->id(),
            'title' => 'Untitled Proposal',
            'theme' => 'midnight',
        ]);

        $proposal->slides()->create([
            'layout' => 'title',
            'content' => [
                'heading' => 'Your Proposal Title',
                'subheading' => 'Subtitle or tagline',
            ],
            'order' => 0,
        ]);

        return redirect()->route('proposal.edit', $proposal);
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $proposal->delete();

        return redirect()->route('proposal')
            ->with('status', 'Proposal deleted.');
    }

    public function print()
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);

        $url = route('proposal.print-view');

        $pdf = Browsershot::url($url)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->margins(0, 0, 0, 0)
            ->windowSize(1123, 794) // A4 landscape in pixels at 96dpi
            ->paperSize(297, 210, 'mm') // explicit A4 landscape
            ->landscape()
            ->setOption('printBackground', true)
            ->waitUntilNetworkIdle()
            ->noSandbox()
            ->timeout(120)
            ->pdf();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="proposal.pdf"',
        ]);
    }
}
