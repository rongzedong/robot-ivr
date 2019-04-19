<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2018/12/16
 * Time: 23:44
 */

namespace App\Services\SmartIvr\Contracts;

/**
 * 可用于转换的数据源
 * Interface Bridgeable
 * @package App\Services\SmartIvr\Contracts
 */
interface Bridgeable
{
    /**
     * 返回转接参数信息
     * @param $taskId string 任务id
     * @param string $bridge_number null 设置转接号码
     * @return array|null [
     *      'number','gateway
     * ]
     */
    public function getBridgeParams($taskId, $bridge_number = null);
}