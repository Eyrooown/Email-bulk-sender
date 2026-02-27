<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardEmails extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'date_desc';

    protected $queryString = ['search' => ['except' => ''], 'sortBy' => ['except' => 'date_desc']];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function getEmailsProperty()
    {
        $query = Email::where('user_id', Auth::id())
            ->where('status', 'sent')
            ->withCount('recipients');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            });
        }

        match ($this->sortBy) {
            'subject_asc'  => $query->orderBy('subject', 'asc'),
            'subject_desc' => $query->orderBy('subject', 'desc'),
            'date_asc'     => $query->orderBy('created_at', 'asc'),
            'date_desc'    => $query->orderBy('created_at', 'desc'),
            default       => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate(25)->withQueryString();
    }

    public function render()
    {
        return view('livewire.dashboard-emails');
    }
}
