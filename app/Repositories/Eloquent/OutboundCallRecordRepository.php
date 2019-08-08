<?php
/**
 * Created by PhpStorm.
 * User: Xingshun <250915790@qq.com>
 * Date: 2019/7/15
 * Time: 18:27
 */

namespace App\Repositories\Eloquent;


use App\Models\OutboundCallRecord;
use App\Models\OutboundNumber;
use Prettus\Repository\Eloquent\BaseRepository;

class OutboundCallRecordRepository extends BaseRepository
{
    public function model()
    {
        return OutboundCallRecord::class;
    }

    /**
     * 获取全程语音文件路径
     * @param string $id 外呼ID
     * @return string
     */
    public function getRecordFile($id)
    {
        $record = $this->scopeQuery(function ($query) use ($id) {
            return $query->whereKey($id);
        })->firstOrNew();

        if (empty($record->recordfile)) {
            abort(404, '语音文件丢失');
        }
        return $record->recordfile;
    }
}