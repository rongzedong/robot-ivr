<?php

namespace App\ScoutElastic\SearchRules;

use ScoutElastic\SearchRule;

/**
 * 精确搜索
 * Class KeywordSearchPreciseRule
 * @package App\ScoutElastic\SearchRules
 */
class KeywordSearchPreciseRule extends SearchRule
{
    public function buildQueryPayload()
    {
        //过滤标点符号
        $query_str = trim(preg_replace('/\p{P}/u', ' ', $this->builder->query));
        return [
            'must' => [
                [
                    'term' => [
                        'name.raw' => $query_str
                    ]
                ],
            ]
        ];
    }
}