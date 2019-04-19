<?php
/**
 * Created by PhpStorm.
 * User: telrobot
 * Date: 2019/1/14
 * Time: 17:17
 */

namespace App\Services\SmartIvr\Contracts;

/**
 * 呼入任务
 * Interface InboundScriptGroupContract
 * @package App\Services\SmartIvr\Contracts
 */
interface InboundTaskContract
{

    /**
     * 话术ID
     * @return integer
     */
    public function getScriptGroupId();

    /**
     * 任务ID
     * @return integer
     */
    public function getTaskId();

}