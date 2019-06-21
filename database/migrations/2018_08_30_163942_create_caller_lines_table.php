<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallerLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 机器人线路表
         */
        Schema::create('caller_lines', function (Blueprint $table) {

            $table->uuid('id');

            $table->tinyInteger('feature')->comment('线路功能类型：呼出/呼入/转接');

            $table->string('call_number_prefix')->comment('主叫号码前缀');

            $table->string('dial_string')->comment('拨号窜');
            $table->tinyInteger('dial_mode')->comment('拨号模式');

            $table->string('gateway', 100)->nullable()->comment('网关名');
            $table->string('realm', 100)->nullable()->comment('网关地址');
            $table->string('proxy', 100)->nullable()->comment('代理地址');
            $table->string('username', 100)->nullable()->comment('网关注册名');
            $table->string('password', 100)->nullable()->comment('密码');
            $table->string('from_domain', 100)->nullable();

            $table->dateTime('deadline_at')->nullable()->comment('截止期限');

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
        Schema::dropIfExists('caller_lines');
    }
}
