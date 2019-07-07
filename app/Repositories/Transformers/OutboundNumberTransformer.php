<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 17:34
 */

namespace App\Repositories\Transformers;


use App\Models\OutboundNumber;
use League\Fractal\TransformerAbstract;

class OutboundNumberTransformer extends TransformerAbstract
{
    public function transform(OutboundNumber $number)
    {
        return [

        ];
    }
}