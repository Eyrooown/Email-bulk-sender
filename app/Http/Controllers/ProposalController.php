<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'title'   => 'Untitled Proposal',
            'theme'   => 'midnight',
        ]);

        $proposal->slides()->create([
            'layout'  => 'title',
            'content' => [
                'heading'    => 'Your Proposal Title',
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
}
