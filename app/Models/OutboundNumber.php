<?php

namespace App\Models;


use App\Traits\Models\ModelTableDividable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

/**
 * 自动外呼任务号码模型
 * Class Manage
 * @package App\Models\AutoDialer
 */
class OutboundNumber extends Model implements Presentable
{
    use ModelTableDividable, SoftDeletes, PresentableTrait;

    protected $fillable = [
        'id',
        'number',
        'state',
        'status',
        'description',
        'recycle',
        'recycle_limit',
        'callid',
        'calldate',
        'bill',
        'duration',
        'hangupcause',
        'hangupdate',
        'answerdate',
        'calleridnumber',
        'bridge_callid',
        'bridge_number',
        'bridge_calldate',
        'bridge_answerdate',
        'recordfile',
    ];

    protected $casts = [
        'duration' => 'int',
        'bill' => 'int',
        'number' => 'string'
    ];

    /**
     * 动态获取表名
     * @return string
     */
    public function getTable()
    {
        return 'autodialer_number_' . self::getTableIdentification();
    }

    /**
     * 高阶消息传递 重新呼叫
     * @param OutboundTask|null $task
     */
    public function resetCall(OutboundTask $task = null)
    {
        if (is_null($task)) {
            //为了优化一个任务批量重呼时，重复获取任务信息
            $task = OutboundTask::query()->findOrFail(self::getTableIdentification());
        }

        //更新当前号码状态
        $this->recordfile = '';
        $this->hangupcause = null;
        $this->fill([
            'status' => null,
            'description' => null,
            'callid' => null,
            'calldate' => Carbon::now(),
            'bill' => 0,
            'duration' => 0,
            'hangupdate' => null,
            'answerdate' => null,
            'calleridnumber' => null,
            'bridge_callid' => null,
            'bridge_number' => null,
            'bridge_calldate' => null,
            'bridge_answerdate' => null,
            'state' => null,
            'recycle_limit' => $task->recycle_limit,
            'recycle' => 0,
        ])->save();
    }

    /**
     * 创建任务号码表
     * @param string $task_id 任务UUID
     * @return bool
     */
    public static function createTable($task_id)
    {
        self::setTableIdentification($task_id);
        /**
         * 任务号码表，动态生成 表名格式 autodialer_number_{任务uuid}
         */
        Schema::create("autodialer_number_{$task_id}", function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('number', 20)->comment('电话号码');

            $table->unsignedTinyInteger('state')->nullable()
                ->comment('号码状态,NULL:未分配 ;1:alloc (等待呼叫);2: originate(呼叫中);3:answer;5:bridge');

            $table->string('status')->nullable()->comment('号码状态，空号关机等	需要配合空号检测模块才效，单独自动外呼程序，无法检测号码状态');
            $table->string('description')->nullable()->comment('号码状态描述');
            $table->unsignedInteger('recycle')->nullable()->comment('回收次数');
            $table->unsignedInteger('recycle_limit')->nullable()->comment('回收次数限制');
            $table->uuid('callid')->nullable();
            $table->dateTime('calldate')->nullable()->comment('呼叫时间');
            $table->unsignedInteger('bill')->nullable()->comment('应答后开始计费的毫秒数');
            $table->unsignedInteger('duration')->nullable()->comment('从开始呼叫到挂断的户毫秒数');
            $table->string('hangupcause')->nullable()->comment('挂断原因');
            $table->dateTime('hangupdate')->nullable();
            $table->dateTime('answerdate')->nullable();

            $table->string('recordfile')->nullable()->comment('录音文件路径');
            $table->string('calleridnumber')->nullable()->comment('外呼的主叫号码');

            //转接功能字段，接通后转机到其他电话才会写入这些数据
            $table->string('bridge_callid')->nullable()->comment('桥接通话ID');
            $table->string('bridge_number')->nullable()->comment('桥接号码');
            $table->dateTime('bridge_calldate')->nullable()->comment('桥接开始时间');
            $table->dateTime('bridge_answerdate')->nullable()->comment('桥接应答时间');
            $table->unsignedInteger('time')->default(0)->comment('拨打次数');
            $table->timestamps();
            $table->softDeletes();
            //号码回收扫描索引
            $table->index(['state', 'status', 'bill', 'duration'], 'state_status_bill_duration_index');
        });

        return Schema::hasTable("autodialer_number_{$task_id}");
    }

    /**
     * 删除任务号码表
     * @param string $task_id 任务UUID
     * @return bool
     */
    public static function dropTable($task_id)
    {
        Schema::dropIfExists("autodialer_number_{$task_id}");
        return !Schema::hasTable("autodialer_number_{$task_id}");
    }

    /**
     * 检查表是否存在
     * @param $task_id
     * @return bool
     */
    public static function hasTable($task_id)
    {
        return Schema::hasTable("autodialer_number_{$task_id}");
    }

    /**
     * 作用域  可在次回播的
     * @param $query
     * @return $this
     */
    public function scopeRecycleEnable($query)
    {
        return $query->whereRaw('recycle_limit > recycle');
    }

    /**
     * 作用域 呼叫失败（线路问题）
     * @inheritdoc
     */
    public function scopeLineFailed($query)
    {
        return $query->where('state', 10)
            ->whereNull('status')
            ->where('bill', 0)
            ->where('duration', '<', 10000);
    }

}
