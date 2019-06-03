<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Freeswitch自动外呼
 * Class CreateAutodialerTable
 */
class CreateAutodialerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 任务表
         */
        Schema::create('autodialer_task', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name')->nullable()->comment('任务名');
            $table->dateTime('create_datetime')->comment('任务创建时间');
            $table->dateTime('alter_datetime')->nullable()->comment('修改时间');
            $table->unsignedTinyInteger('start')->nullable()->comment('是否启动任务，1：启动');
            $table->unsignedInteger('maximumcall')->comment('最大并发呼叫');
            $table->integer('call_per_second')->default(10)->comment('CPS,每秒最大发起多少个呼叫');
            $table->unsignedTinyInteger('recycle_limit')->nullable()->comment('号码最大回收次数');
            $table->unsignedTinyInteger('random_assignment_number')->nullable()->comment('是否随机分配号码，1:随机分配,其他值顺序分配号码.autodialer import file taskuuid,这个命令之前设置有效。');
            $table->uuid('disable_dial_timegroup')->nullable()->comment('禁止呼叫时间组id，可以设置下班时间自动停止呼叫');
            $table->string('destination_extension')->comment('应答分组id');

            $table->string('destination_dialplan')->default('XML')->comment('应答后转接目的拨号方案	“XML”');
            $table->string('destination_context')->comment('应答后转接目的上下文	“IVR”或者”FIFO”');
            $table->float('scheduling_policy_ratio')->nullable()->comment('外呼比率');
            $table->string('scheduling_queue')->nullable()->comment('调度队列');
            $table->string('dial_format')->comment('拨号串格式');

            $table->string('domain')->nullable()->comment('域名,用于支持多租户');
            $table->string('remark')->nullable()->comment('备注');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->text('originate_variables')->nullable()->comment('用于自定于呼出参数，需要用{}包围参数');
            $table->unsignedInteger('_originate_timeout')->nullable()->comment('拨号超时');
            $table->string('_origination_caller_id_number')->nullable()->comment('主叫号码');
            $table->uuid('user_id')->comment('任务创建人');
            $table->uuid('caller_line_id')->nullable()->comment('线路关联id');
            $table->unsignedInteger('customer_service_id')->nullable()->comment('客服转接组');

            $table->string('call_notify_url', 1024)->nullable()->comment('挂断通知URL');
            $table->tinyInteger('call_notify_type')->nullable()->comment('通知类型  0 不通知  1 呼叫失败通知  2 呼叫失败和成功都通知');
        });

        /**
         * 日志表
         */
        Schema::create('autodialer_log', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->dateTime('create_datetime')->comment('日志时间');
            $table->string('table_name')->comment('关联的表名');
            $table->uuid('related_id')->nullable()->comment('关联的表记录id');
            $table->string('domain')->nullable()->comment('域名');
            $table->text('content')->comment('日志内容');

        });

        /**
         * 禁止呼叫时间组
         */
        Schema::create('autodialer_timegroup', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('name');
            $table->string('domain');
            $table->uuid('user_id')->comment('用户id');
            $table->timestamps();
        });

        /**
         * 时间明细
         */
        Schema::create('autodialer_timerange', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->time('begin_datetime')->comment('开始时间');
            $table->time('end_datetime')->comment('结束时间');
            $table->string('group_uuid')->comment('所属的时间组');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autodialer_timerange');
        Schema::dropIfExists('autodialer_timegroup');
        Schema::dropIfExists('autodialer_log');
        Schema::dropIfExists('autodialer_task');
    }
}
