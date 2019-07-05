<?php

/** @var \Illuminate\Support\Facades\Route $router */


/**
 *  外呼任务
 */
$router->get('task/outbound/start', 'Api\Task\Outbound@start');

$router->get('task/outbound/stop', 'Api\Task\Outbound@stop');

$router->post('task/outbound', 'Api\Task\Outbound@store');

$router->put('task/outbound/{id}', 'Api\Task\Outbound@update');

$router->delete('task/outbound/{id}', 'Api\Task\Outbound@destroy');

/**
 * 外呼号码
 */
$router->get('task/{task_id}/outbound/number', 'Api\Task\OutboundNumber@index');

$router->post('task/{task_id}/outbound/number', 'Api\Task\OutboundNumber@store');

$router->put('task/{task_id}/outbound/number/{id}', 'Api\Task\OutboundNumber@update');

$router->delete('task/{task_id}/outbound/number/{id}', 'Api\Task\OutboundNumber@destroy');