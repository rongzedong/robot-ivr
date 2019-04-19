<?php

namespace App\ScoutElastic;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class OutboundLabelIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [
        //
    ];

    public function __construct()
    {
        $this->name = config('scout_elastic.prefix') . 'outbound_label';

        $this->settings = [
            'index' => [
                'number_of_shards' => 5,
                'auto_expand_replicas' => '0-1',
            ],
        ];
    }
}