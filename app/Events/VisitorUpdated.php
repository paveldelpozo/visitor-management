<?php

namespace App\Events;

use App\Models\Visitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisitorUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?Visitor $visitor;

    public function __construct(public Visitor $v)
    {
        $this->visitor = $v->fresh();
    }

    public function broadcastOn(): Channel
    {
        return new Channel('visitors');
    }

    public function broadcastAs(): string
    {
        return 'visitors.updated';
    }
}
