<?php

namespace App\Services\SmartIvr\Events\Notify\Inbound;

use App\Services\SmartIvr\Notify\Inbound\AsrmessageNotify;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AsrMessagedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entity;
    public $request;
    public $question;
    public $index;
    public $model;

    /**
     * Create a new event instance.
     *
     * @param AsrmessageNotify $notify
     */
    public function __construct(AsrmessageNotify $notify)
    {
        $this->entity = $notify->resolveHandle()->getHandleEntity();
        $this->request = $notify->getReceiveData()->toArray();
        $this->question = $notify->getQuestion();
        $this->index = $notify->getIndex();
        $this->model = $notify->getPayload()->getModel();

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
