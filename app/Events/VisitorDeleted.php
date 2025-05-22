<?php

namespace App\Events;

use App\Models\Visitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisitorDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visitorId;

    public function __construct(int $visitorId)
    {
        $this->visitorId = $visitorId;
    }

    public function broadcastOn()
    {
        return new Channel('visitors');
    }

    public function broadcastAs()
    {
        return 'visitor.deleted';
    }
}