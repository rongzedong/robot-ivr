<?php

namespace App\Providers;

use App\Models\OutboundTask;
use App\Observes\OutboundTaskObserve;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\OutboundTaskCreatedEvent' => [
            'App\Listeners\ExampleListener',
        ],
    ];

    public function boot()
    {
        parent::boot();

        $this->registerObserves();

    }

    protected function registerObserves()
    {
        OutboundTask::observe(OutboundTaskObserve::class);

    }
}
