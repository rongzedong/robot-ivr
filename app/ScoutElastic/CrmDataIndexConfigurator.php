<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/3/12
 * Time: 16:05
 */

namespace App\ScoutElastic;


use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

/**
 * crm 数据
 * Class CrmDataIndexConfigurator
 * @package App\ScoutElastic
 */
class CrmDataIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [
        //
    ];

    protected $defaultMapping = [
        'dynamic' => true,
        'properties' => [
            'number' => [
                'type' => 'text',
                'analyzer' => 'tel_number_analyzer',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ],
            ],
            'user_id' => [
                'type' => 'keyword',
            ],
            'created_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
            'updated_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
        ]
    ];

    /**
     * CrmDataIndexConfigurator constructor.
     */
    public function __construct()
    {
        $this->name = config('scout_elastic.prefix') . 'crm_data';

        $this->settings = [
            'index' => [
                'number_of_shards' => 5,
                'auto_expand_replicas' => '0-1',
            ],
            'analysis' => [
                'analyzer' => [
                    //电话号码分析器
                    'tel_number_analyzer' => [
                        'tokenizer' => 'tel_number_tokenizer',
                        'char_filter' => 'tel_number_char_filter',
                    ]
                ],
                'tokenizer' => [
                    'tel_number_tokenizer' => [
                        'type' => 'ngram',
                        'min_gram' => 1,
                        'max_gram' => 1,
                        'token_chars' => [
                            'digit'
                        ]
                    ]
                ],
                'char_filter' => [
                    'tel_number_char_filter' => [
                        'type' => 'pattern_replace',
                        'pattern' => '(\d+)[-_\s](?=\d)',
                        'replacement' => '$1',
                    ]
                ]
            ],
        ];
    }
}