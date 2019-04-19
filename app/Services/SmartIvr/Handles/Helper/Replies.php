<?php
/**
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/4/2
 * Time: 22:45
 */

namespace App\Services\SmartIvr\Handles\Helper;


/**
 * 话术应答队列
 * Class AnswersCache
 * @package App\Services\SmartIvr
 */
class Replies extends Base
{

    /**
     * 入列
     * @param string|array $value
     */
    public function push($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        if ($this->cache->has($this->cache_key)) {
            $original = $this->cache->get($this->cache_key);
            foreach ($value as $v) {
                array_push($original, $v);
            }
            $value = $original;
        }

        $this->cache->forever($this->cache_key, $value);
    }

    /**
     * 出列
     * @return mixed|null
     */
    public function pop()
    {
        if ($this->cache->has($this->cache_key)) {
            $value = $this->cache->get($this->cache_key);

            $data = array_shift($value);

            if (empty($value)) {
                //删除
                $this->cache->forget($this->cache_key);
            } else {
                $this->cache->forever($this->cache_key, $value);
            }
            return $data;
        } else {
            return null;
        }

    }


}