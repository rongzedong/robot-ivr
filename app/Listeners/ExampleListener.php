<?php

namespace App\Listeners;

use App\Events\OutboundTaskCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExampleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OutboundTaskCreatedEvent  $event
     * @return void
     */
    public function handle(OutboundTaskCreatedEvent $event)
    {
        //
    }
}
