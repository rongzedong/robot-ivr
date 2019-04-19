<?php

namespace App\Services\SmartIvr;

use App\Repositories\Inbound\InboundTaskRepository;
use App\Services\SmartIvr\Contracts\InboundContract;
use Illuminate\Support\ServiceProvider;

class SmartIvrProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //呼入与呼入任务对接
        $this->app->instance(InboundContract::class, InboundTaskRepository::instance());
    }

}
