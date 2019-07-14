<?php

/** @var \Illuminate\Support\Facades\Route $router */

/**
 * 通话语音播放
 */
$router->get('outbound/{task_id}/{number_id}/voice_playing', 'Api\Task\VoicePlaying@outboundRecoding');