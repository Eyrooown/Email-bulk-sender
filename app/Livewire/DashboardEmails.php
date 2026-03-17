<?php

namespace App\Livewire;

use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardEmails extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'date_desc';

    public string $dateFrom = '';
    public string $dateTo = '';
    public ?int $recipientsMin = null;
    public ?int $recipientsMax = null;

    public array $selectedIds = [];
    public bool $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'date_desc'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'recipientsMin' => ['except' => null],
        'recipientsMax' => ['except' => null],
    ];

    public function updatedSearch()    { $this->resetPage(); }
    public function updatedSortBy()    { $this->resetPage(); }
    public function updatedDateFrom()  { $this->resetPage(); }
    public function updatedDateTo()    { $this->resetPage(); }
    public function updatedRecipientsMin() { $this->resetPage(); }
    public function updatedRecipientsMax() { $this->resetPage(); }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->emails->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'date_desc';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->recipientsMin = null;
        $this->recipientsMax = null;
        $this->resetPage();
    }

    public function deleteEmail(int $id): void
    {
        $email = Email::where('user_id', Auth::id())->findOrFail($id);
        $email->delete();
        $this->selectedIds = array_filter($this->selectedIds, fn ($i) => (int) $i !== $id);
    }

    public function deleteSelected(): void
    {
        Email::where('user_id', Auth::id())
            ->whereIn('id', $this->selectedIds)
            ->delete();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function getEmailsProperty()
    {
        $query = Email::where('user_id', Auth::id())
            ->where('status', 'sent')
            ->withCount([
                'recipients',
                'recipients as recipients_sent_count' => fn ($q) => $q->where('status', 'sent'),
            ]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            });
        }

        try {
            if ($this->dateFrom) {
                $fromUtc = Carbon::parse($this->dateFrom, 'Asia/Manila')->startOfDay()->utc();
                $query->where('created_at', '>=', $fromUtc);
            }
            if ($this->dateTo) {
                $toUtc = Carbon::parse($this->dateTo, 'Asia/Manila')->endOfDay()->utc();
                $query->where('created_at', '<=', $toUtc);
            }
        } catch (\Throwable $e) {
            // ignore invalid date input
        }

        if (!is_null($this->recipientsMin) && $this->recipientsMin !== '') {
            $query->having('recipients_count', '>=', (int) $this->recipientsMin);
        }
        if (!is_null($this->recipientsMax) && $this->recipientsMax !== '') {
            $query->having('recipients_count', '<=', (int) $this->recipientsMax);
        }

        match ($this->sortBy) {
            'subject_asc'  => $query->orderBy('subject', 'asc'),
            'subject_desc' => $query->orderBy('subject', 'desc'),
            'date_asc'     => $query->orderBy('created_at', 'asc'),
            'date_desc'    => $query->orderBy('created_at', 'desc'),
            default        => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate(25)->withQueryString();
    }

    public function render()
    {
        return view('livewire.dashboard-emails');
    }
}
