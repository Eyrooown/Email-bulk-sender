<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SendingProgressToast extends Component
{
    public bool $show = false;
    public int $current = 0;
    public int $total = 0;
    public ?int $emailId = null;

    public function mount()
    {
        $emailId = Cache::get('sending_active_' . Auth::id());
        if ($emailId) {
            $this->emailId = $emailId;
            $this->show = true;

            // Load current progress immediately to avoid 0/0 flicker
            $progress = Cache::get("sending_progress_{$emailId}");
            if ($progress) {
                $this->current = $progress['current'];
                $this->total = $progress['total'];
            }
        }
    }

    protected function getListeners()
    {
        return [
            'startProgressToast' => 'startProgressToast',
        ];
    }

    public function startProgressToast($emailId, $total)
    {
        $this->emailId = $emailId;
        $this->total = $total;
        $this->current = 0;
        $this->show = true;

        // Store active send in cache so other pages can pick it up
        Cache::put('sending_active_' . Auth::id(), $emailId, 300);
    }

    public function checkProgress()
    {
        if (!$this->emailId) return;

        $progress = Cache::get("sending_progress_{$this->emailId}");
        if ($progress) {
            $this->current = $progress['current'];
            $this->total = $progress['total'];

            if ($progress['currentEmail'] === 'done') {
                $this->show = false;
                $this->emailId = null;
                Cache::forget('sending_active_' . Auth::id());
            }
        }
    }

    public function render()
    {
        return view('livewire.sending-progress-toast');
    }
}
