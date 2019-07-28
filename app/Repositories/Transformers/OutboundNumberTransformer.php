<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/7
 * Time: 17:34
 */

namespace App\Repositories\Transformers;


use App\Models\OutboundNumber;
use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;

class OutboundNumberTransformer extends TransformerAbstract
{
    public function transform(OutboundNumber $number)
    {
        return $number->only([
                'id',
                'state',
                'status',
                'description',
                'bill',
                'callid',
                'duration',
                'calldate',
                'hangupcause',
                'hangupdate',
                'answerdate',
                'recycle',
                'bridge_callid',
                'bridge_number',
                'bridge_calldate',
                'bridge_answerdate',
            ]) + [
                'recordfile' => URL::asset('/v1/outbound/' . OutboundNumber::getTableIdentification() . '/' . $number->getKey() . '/voice_playing'),
            ];
    }
}