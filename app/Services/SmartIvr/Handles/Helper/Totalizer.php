<?php
/**
 * Created by PhpStorm.
 * User: YangXingshun
 * Date: 2018/11/2
 * Time: 15:08
 */

namespace App\Services\SmartIvr\Handles\Helper;

use Illuminate\Support\Facades\Cache;

/**
 * 计数累加器
 * Class Totalizer
 */
class Totalizer
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * 累加
     * @param int $amount
     * @return int
     * @throws \Exception
     */
    public function increment($amount = 1)
    {
        return Cache::increment($this->key, $amount);
    }

    /**
     * 当前值
     * @return int
     */
    public function current()
    {
        return (int)Cache::get($this->key, 0);
    }
}