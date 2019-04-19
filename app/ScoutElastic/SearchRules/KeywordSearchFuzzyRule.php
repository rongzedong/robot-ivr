<?php

namespace App\ScoutElastic\SearchRules;

use ScoutElastic\SearchRule;

/**
 * 模糊匹配规则
 * Class KeywordSearchFuzzyRule
 * @package App\ScoutElastic\SearchRules
 * @author Xingshun <250915790@qq.com>
 */
class KeywordSearchFuzzyRule extends SearchRule
{
    public function buildQueryPayload()
    {
        $query_str = trim(preg_replace('/\p{P}/u', ' ', $this->builder->query));
        return [
            'must' => [
                'match' => [
                    'name' => [
                        'query' => $query_str,
                        'fuzzy_transpositions' => false,
                        'minimum_should_match' => config('scout_elastic.minimum_should_match', '100%')
                    ]
                ]
            ],
            'filter' => [
                'bool' => [
                    'must' => [
                        [
                            'terms' => [
                                'identify_type' => [1, 3, 5, 7],//模糊类型的词条
                            ]
                        ]
                    ]
                ]
            ],
            'should' => [
                'match_phrase' => [
                    'name' => [
                        'query' => $query_str,
                        'slop' => 1
                    ]
                ]
            ]
        ];
    }
}