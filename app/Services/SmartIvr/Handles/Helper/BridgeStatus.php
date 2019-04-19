<?php

namespace App\Services\SmartIvr\Handles\Helper;


/**
 * 转接状态
 * Class Flag
 * @package Services\SmartIvr\Dialer\Caches
 * @author Xingshun <250915790@qq.com>
 */
class BridgeStatus extends Base
{

    const PENDING = 1;

    const FAILED = 2;

    const SUCCESS = 4;

    /**
     * 设置状态
     * @param $status
     * @return $this
     */
    public function set($status)
    {
        $this->cache->forever($this->cache_key, $status);
        return $this;
    }

    /**
     * 获取状态
     * return int
     */
    public function get()
    {
        return $this->cache->get($this->cache_key);
    }

    /**
     * 用户是否做出回应
     * @param int $status
     * @return boolean
     */
    public function is($status)
    {
        return $this->get() & $status;
    }


}