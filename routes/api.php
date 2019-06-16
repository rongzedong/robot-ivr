<?php

/** @var \Illuminate\Support\Facades\Route $router */

$router->post('task/outbound', 'Api\Task\Outbound@store');

$router->put('task/outbound/{id}', 'Api\Task\Outbound@update');

$router->delete('task/outbound/{id}', 'Api\Task\Outbound@destroy');