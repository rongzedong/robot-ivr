<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/3/3
 * Time: 21:51
 */

namespace App\Services\SmartIvr\Handles\Helper;

/**
 * 通话干涉
 * Class Intervene
 * @package App\Services\SmartIvr\Handles\Helper
 */
class Intervene extends Base
{

    const MODE_BRIDE = 'bridge';

    const MODE_HANGUP = 'hangup';

    /**
     * 介入记录ID
     * @param $id
     * @return $this
     */
    public function recordId($id)
    {
        $this->cache->forever($this->cache_key . '_record_id', $id);
        return $this;
    }

    public function getRecordId()
    {
        return $this->cache->get($this->cache_key . '_record_id');
    }

    /**
     * 强制转接
     * @param $number
     * @return $this
     */
    public function bridge($number)
    {
        $this->cache->forever($this->cache_key, self::MODE_BRIDE);
        $this->cache->forever($this->cache_key . self::MODE_BRIDE . '_number', $number);
        return $this;
    }

    /**
     * 获取转接号码
     * @return mixed
     */
    public function getBridgeNumber()
    {
        return $this->cache->get($this->cache_key . self::MODE_BRIDE . '_number');
    }

    public function isBridge()
    {
        return $this->cache->get($this->cache_key) == self::MODE_BRIDE;
    }

    /**
     * 强制挂断
     * @return $this
     */
    public function hangup()
    {
        $this->cache->forever($this->cache_key, self::MODE_HANGUP);
        return $this;
    }

    /**
     * @return bool
     */
    public function isHangup()
    {
        return $this->cache->get($this->cache_key) == self::MODE_HANGUP;
    }

}