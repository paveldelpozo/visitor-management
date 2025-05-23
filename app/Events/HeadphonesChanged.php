<?php

namespace App\Events;

use App\Models\Visitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HeadphonesChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Visitor $visitor;
    public int $change;

    public function __construct(Visitor $visitor, int $change)
    {
        $this->visitor = $visitor;
        $this->change = $change;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('visitors');
    }

    public function broadcastAs(): string
    {
        return 'visitors.headphones.changed';
    }
}
