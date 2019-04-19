<?php
/**
 * Created by PhpStorm.
 * User: Yxs <250915790@qq.com>
 * Date: 2019/2/25
 * Time: 16:46
 */

namespace App\Services\SmartIvr\Contracts;


interface HandleGetPayloadContract
{

    /**
     * HandleGetPayloadContract constructor.
     * @param HandleContract $handle
     */
    public function __construct(HandleContract $handle);

    /**
     * @param NotifyContract|null $notify
     * @return mixed
     */
    public function getPayload(NotifyContract $notify = null);
}