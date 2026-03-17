<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ArchiveEmails extends Component
{
    use WithPagination;

    public string $search = '';
    public array $selectedIds = [];
    public bool $selectAll = false;

    public function updatedSearch() { $this->resetPage(); }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIds = $this->emails->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function restoreEmail(int $id): void
    {
        Email::onlyTrashed()
            ->where('user_id', Auth::id())
            ->findOrFail($id)
            ->restore();
        $this->selectedIds = array_filter($this->selectedIds, fn ($i) => (int) $i !== $id);
    }

    public function restoreSelected(): void
    {
        Email::onlyTrashed()
            ->where('user_id', Auth::id())
            ->whereIn('id', $this->selectedIds)
            ->restore();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function getEmailsProperty()
    {
        $query = Email::onlyTrashed()
            ->where('user_id', Auth::id())
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

        return $query->orderBy('deleted_at', 'desc')->paginate(25);
    }

    public function render()
    {
        return view('livewire.archive-emails');
    }
}
