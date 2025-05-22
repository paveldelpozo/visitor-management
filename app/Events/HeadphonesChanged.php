<?php

namespace App\Events;

use App\Models\Visitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HeadphonesChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $visitor;
    public $change;

    public function __construct(Visitor $visitor, int $change)
    {
        $this->visitor = $visitor;
        $this->change = $change;
    }

    public function broadcastOn()
    {
        return new Channel('visitors');
    }

    public function broadcastAs()
    {
        return 'visitor.headphones.changed';
    }
}