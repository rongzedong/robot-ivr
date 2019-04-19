<?php

namespace App\Services\SmartIvr\Events\Notify\Inbound;

use App\Services\SmartIvr\Notify\Inbound\StartAsrResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartAsrResultedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notify;

    /**
     * Create a new event instance.
     *
     * @param StartAsrResult $notify
     */
    public function __construct(StartAsrResult $notify)
    {
        info('[inbound]', $notify->getReceiveData()->toArray());
        //$this->notify = $notify;
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
