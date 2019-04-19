<?php

namespace App\Services\SmartIvr\Handles\Helper;

use App\Services\SmartIvr\Contracts\ModelContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * 应答记录缓存
 * Class RecordCache
 * @package Services\SmartIvr\Dialer
 * @author Xingshun <250915790@qq.com>
 */
class Records extends Base
{

    /**
     * 入
     * @param $model
     */
    public function push($model)
    {
        $value = [
            'id' => $model->getKey(),
            'frequency' => 1,
            'created_at' => Carbon::now(),
        ];
        if (!$this->has()) {
            $this->cache->forever($this->cache_key, collect()->push($value));
        } else {
            $flag = true;//追加标记
            $collect = $this->get()->map(function ($item) use ($model, &$flag) {
                if ($model->getKey() == $item['id']) {
                    $flag = false;
                    $item['frequency'] += 1;//添加次数
                }
                return $item;
            });

            if ($flag) {
                $collect->push($value);
            }
            $this->cache->forever($this->cache_key, $collect);
        }

    }

    /**
     * 判断是否存在
     * @return bool
     */
    public function has()
    {
        return $this->cache->has($this->cache_key);
    }

    /**
     * 获取通话话术集合
     * @return Collection
     */
    public function get()
    {
        return $this->cache->get($this->cache_key);
    }


}