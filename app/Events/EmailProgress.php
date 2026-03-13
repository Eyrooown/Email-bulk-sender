<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailProgress implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $livewireId,
        public int $current,
        public int $total,
        public string $currentEmail
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('email-progress.' . $this->livewireId);
    }

    public function broadcastAs(): string
    {
        return 'progress';
    }
}
