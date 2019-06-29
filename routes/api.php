<?php

/** @var \Illuminate\Support\Facades\Route $router */

$router->get('task/outbound/start', 'Api\Task\Outbound@start');

$router->get('task/outbound/stop', 'Api\Task\Outbound@stop');

$router->post('task/outbound', 'Api\Task\Outbound@store');

$router->put('task/outbound/{id}', 'Api\Task\Outbound@update');

$router->delete('task/outbound/{id}', 'Api\Task\Outbound@destroy');

