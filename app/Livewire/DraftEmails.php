<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DraftEmails extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getEmailsProperty()
    {
        $query = Email::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->withCount('recipients')
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            });
        }

        return $query->paginate(25)->withQueryString();
    }

    public function render()
    {
        return view('livewire.draft-emails');
    }
}
