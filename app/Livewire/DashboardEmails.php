<?php

namespace App\Livewire;

use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardEmails extends Component
{
    public string $search = '';

    public function getEmailsProperty()
    {
        $query = Email::where('user_id', Auth::id())
            ->withCount('recipients')
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orWhere('body', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.dashboard-emails');
    }
}
