<?php

/** @var \Illuminate\Support\Facades\Route $router */

/**
 * 通话语音播放
 */
$router->get('outbound/{call_id}/voice_playing.wav', 'Api\Task\VoicePlaying@outboundRecoding');

/**
 * asr 语音
 */
$router->get('asr/{path}/{filename}/voice_playing.wav','Api\Task\AsrVoicePlaying@index');