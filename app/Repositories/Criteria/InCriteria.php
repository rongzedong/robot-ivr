<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/13
 * Time: 1:10
 */

namespace App\Repositories\Criteria;


use Prettus\Repository\Criteria\RequestCriteria;

class InCriteria extends RequestCriteria
{


    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = stripos($value, '-') ? explode('-', $value) : $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {

        $search = parent::parserSearchValue($search);

        if (stripos($search, '-')) {
            return explode('-', $search);
        }

        dd($search);
        return $search;
    }
}