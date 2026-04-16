<?php

namespace App\Livewire;

use App\Models\Email;
use App\Models\EmailRecipient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LeadsDashboard extends Component
{
    public string $selectedCampaignId = 'all';

    protected $queryString = [
        'selectedCampaignId' => ['except' => 'all'],
    ];

    public function markAsReplied(int $recipientId): void
    {
        $query = EmailRecipient::query()->where('status', 'sent');

        if (!Auth::user()?->is_admin) {
            $query->whereHas('campaign', fn (Builder $emailQuery) => $emailQuery->where('user_id', Auth::id()));
        }

        if ($this->selectedCampaignId !== 'all') {
            $query->where('email_id', (int) $this->selectedCampaignId);
        }

        $recipient = $query->findOrFail($recipientId);
        $recipient->update(['status' => 'replied']);
    }

    private function campaignQuery(): Builder
    {
        $query = Email::query()->where('status', 'sent');

        if (!Auth::user()?->is_admin) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    private function recipientsQuery(): Builder
    {
        $query = EmailRecipient::query()
            ->whereIn('status', ['sent', 'replied'])
            ->with(['campaign:id,user_id,subject,created_at', 'campaign.user:id,name']);

        if (!Auth::user()?->is_admin) {
            $query->whereHas('campaign', fn (Builder $emailQuery) => $emailQuery->where('user_id', Auth::id()));
        }

        if ($this->selectedCampaignId !== 'all') {
            $query->where('email_id', (int) $this->selectedCampaignId);
        }

        return $query;
    }

    private function recipientsBaseQuery(): Builder
    {
        $query = EmailRecipient::query()
            ->whereIn('status', ['sent', 'replied'])
            ->with(['campaign:id,user_id,subject,created_at', 'campaign.user:id,name']);

        if (!Auth::user()?->is_admin) {
            $query->whereHas('campaign', fn (Builder $emailQuery) => $emailQuery->where('user_id', Auth::id()));
        }

        return $query;
    }

    public function render()
    {
        $campaigns = $this->campaignQuery()
            ->withCount([
                'recipients',
                'recipients as replied_count' => fn (Builder $query) => $query->where('status', 'replied'),
            ])
            ->latest()
            ->get(['id', 'subject', 'created_at']);

        $globalRecipients = $this->recipientsBaseQuery()->latest()->get();
        $tableRecipients = $this->recipientsQuery()->latest()->get();

        $totalSent = $globalRecipients->count();
        $totalReplied = $globalRecipients->where('status', 'replied')->count();
        $totalNoReply = $globalRecipients->where('status', 'sent')->count();
        $replyRate = $totalSent > 0 ? round(($totalReplied / $totalSent) * 100) : 0;

        $weeks = collect(range(7, 0))->map(function (int $offset) {
            $start = Carbon::now()->startOfWeek()->subWeeks($offset);
            $end = (clone $start)->endOfWeek();

            return [
                'label' => $start->format('M j'),
                'start' => $start,
                'end' => $end,
            ];
        });

        $chartLabels = $weeks->pluck('label')->toArray();
        $chartSent = [];
        $chartReplied = [];
        $chartNoReply = [];

        foreach ($weeks as $week) {
            $weeklyRecipients = $globalRecipients->filter(
                fn (EmailRecipient $recipient) => $recipient->created_at->between($week['start'], $week['end'])
            );

            $sentCount = $weeklyRecipients->count();
            $repliedCount = $weeklyRecipients->where('status', 'replied')->count();

            $chartSent[] = $sentCount;
            $chartReplied[] = $repliedCount;
            $chartNoReply[] = max(0, $sentCount - $repliedCount);
        }

        return view('livewire.leads-dashboard', [
            'campaigns' => $campaigns,
            'totalSent' => $totalSent,
            'totalNoReply' => $totalNoReply,
            'totalReplied' => $totalReplied,
            'replyRate' => $replyRate,
            'allSends' => $tableRecipients->take(8),
            'noReplySends' => $tableRecipients->where('status', 'sent')->take(8),
            'repliedSends' => $tableRecipients->where('status', 'replied')->take(8),
            'chartData' => [
                'labels' => $chartLabels,
                'sent' => $chartSent,
                'replied' => $chartReplied,
                'noReply' => $chartNoReply,
            ],
        ]);
    }
}
