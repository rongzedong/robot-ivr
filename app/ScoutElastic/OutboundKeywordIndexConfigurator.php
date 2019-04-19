<?php

namespace App\ScoutElastic;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class OutboundKeywordIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [];

    protected $name;

    public function __construct()
    {
        $this->name = config('scout_elastic.prefix') . 'outbound_keyword';

        $this->settings = [
            'index' => [
                'number_of_shards' => 5,
                'auto_expand_replicas' => '0-1',
            ],
            'analysis' => [
                'filter' => [
                    'my_stopwords' => [
                        'type' => 'stop',
                        'stopwords' => []
                    ]
                ],

                'analyzer' => [
                    //中文分词器 ik_max_word,ik_smart,
                    'ik_smart_analyzer' => [
                        'tokenizer' => 'ik_smart',
                        'filter' => [
                            'my_stopwords',
                        ]
                    ],
                    'ik_max_word_analyzer' => [
                        'tokenizer' => 'ik_max_word',
                        'filter' => [
                            'my_stopwords',
                        ]
                    ],
                    //空白分词
                    'whitespace_analyzer' => [
                        'tokenizer' => 'whitespace',
                        'filter' => [
                            'my_stopwords',
                            'lowercase'
                        ]
                    ],
                ]
            ]
        ];
    }

}