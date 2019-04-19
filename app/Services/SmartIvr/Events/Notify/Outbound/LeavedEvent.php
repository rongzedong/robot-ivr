<?php

namespace App\Services\SmartIvr\Events\Notify\Outbound;

use App\Services\SmartIvr\Notify\Outbound\Leave;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeavedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notify;
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param Leave $notify
     */
    public function __construct(Leave $notify)
    {
        $this->notify = $notify;
        $this->entity = $notify->resolveHandle()->getHandleEntity();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
