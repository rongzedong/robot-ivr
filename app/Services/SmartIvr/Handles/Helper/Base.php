<?php


namespace App\Services\SmartIvr\Handles\Helper;

use App\Traits\Singleton;
use Illuminate\Support\Facades\Cache;

abstract class Base
{
    use Singleton;

    protected $cache_key;

    protected $cache_tag;

    protected $cache;

    /**
     * AnswersCache constructor.
     */
    public function __construct()
    {
        $this->setCacheTag();

        $this->cache = Cache::tags($this->cache_tag);
    }

    protected function setCacheTag()
    {
        $this->cache_tag = 'smart_ivr_notify_' . md5(class_basename($this));
    }

    /**
     * 设置缓存key
     * @param $key
     * @return $this
     */
    public function setCacheKey($key)
    {
        $this->cache_key = $this->cache_tag . $key;
        return $this;
    }

    /**
     * 清除
     */
    public function forget()
    {
        $this->cache->forget($this->cache_key);
    }


}