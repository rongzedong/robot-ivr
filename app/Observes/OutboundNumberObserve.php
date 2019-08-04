<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/8/4
 * Time: 21:10
 */

namespace App\Observes;


use App\Models\OutboundNumber;

class OutboundNumberObserve
{
    public function deleting(OutboundNumber $model)
    {
        //软删除时，防止继续呼叫
        $model->state = 10;

        $model->save();
    }
}