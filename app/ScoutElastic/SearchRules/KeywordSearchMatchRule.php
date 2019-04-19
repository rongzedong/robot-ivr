<?php

namespace App\ScoutElastic\SearchRules;

use Illuminate\Support\Str;
use ScoutElastic\SearchRule;

/**
 * 包含
 * Class KeywordSearchMatchRule
 * @package App\ScoutElastic\SearchRules
 */
class KeywordSearchMatchRule extends SearchRule
{
    public function buildQueryPayload()
    {
        //过滤标点符号
        $query_str = trim(preg_replace('/\p{P}/u', ' ', $this->builder->query));
        return [
            'must' => [
                [
                    'match' => [
                        'name' => [
                            'query' => $query_str,
                            'fuzzy_transpositions' => false
                        ],
                    ]
                ],
                [
                    'script' => [
                        'script' => [
                            'lang' => 'painless',
                            'source' => "String v = '{$query_str}';return v.indexOf(doc['name.raw'].value) >= 0;",
                        ]
                    ]
                ]
            ],
            'filter' => [
                'bool' => [
                    'must' => [
                        [
                            'terms' => [
                                'identify_type' => [2, 3, 6, 7],//包含类型的词条
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}