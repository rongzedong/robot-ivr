<?php

namespace App\Services\SmartIvr\Handles\Helper;

use App\Traits\Singleton;

/**
 * 通话标记
 * Class Flag
 * @package Services\SmartIvr\Dialer\Caches
 * @author Xingshun <250915790@qq.com>
 */
class Reply extends Base
{
    /**
     * 标记用户是否做出回应
     * @param boolean|true $is_reply 是否回应
     */
    public function setReply($is_reply = true)
    {
        $this->cache->forever($this->cache_key, $is_reply);
    }

    /**
     * 用户是否做出回应
     * @return boolean
     */
    public function hasReply()
    {
        return (bool)$this->cache->pull($this->cache_key);
    }


}