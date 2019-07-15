<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/15
 * Time: 18:27
 */

namespace App\Repositories\Eloquent;


use App\Models\OutboundCallRecord;
use Prettus\Repository\Eloquent\BaseRepository;

class OutboundCallRecordRepository extends BaseRepository
{
    public function model()
    {
        return OutboundCallRecord::class;
    }


}