<?php

namespace App\Services\SmartIvr\Events\Notify\Outbound;

use App\Services\SmartIvr\Contracts\PayloadContract;
use App\Services\SmartIvr\Handles\Helper\HandleEntity;
use App\Services\SmartIvr\Notify\Outbound\Enter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EnteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array 请求参数
     */
    public $request;

    /**
     * @var \App\Services\SmartIvr\Contracts\ModelContract|null
     */
    public $model;

    /**
     * @var HandleEntity $entity
     */
    public $entity;

    /**
     * Create a new event instance.
     *
     * @param Enter $notify
     */
    public function __construct(Enter $notify)
    {
        $this->entity = $notify->resolveHandle()->getHandleEntity();

        $this->request = $notify->getReceiveData()->toArray();

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
