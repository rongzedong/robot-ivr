<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutboundCallRecordsTable extends Migration
{
    /**
     *
     *
     * @return void
     */
    public function up()
    {
        /**
         * 外呼通话数据记录表
         */
        Schema::create('outbound_call_records', function (Blueprint $table) {
            $table->uuid('id')->comment('通话ID');
            $table->string('number', 20)->comment('电话号码');

            $table->unsignedTinyInteger('state')->nullable()
                ->comment('号码状态,NULL:未分配 ;1:alloc (等待呼叫);2: originate(呼叫中);3:answer;5:bridge');

            $table->string('status')->nullable()->comment('号码状态，空号关机等	需要配合空号检测模块才效，单独自动外呼程序，无法检测号码状态');
            $table->string('description')->nullable()->comment('号码状态描述');
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
        Schema::dropIfExists('call_records');
    }
}
