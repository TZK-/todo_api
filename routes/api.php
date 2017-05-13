<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

$app->group(['prefix' => 'todos'], function () use ($app) {
    $app->get('', 'TodoController@index');
    $app->post('', 'TodoController@create');
    $app->get('{id}', 'TodoController@show');
    $app->put('{id}', 'TodoController@update');
    $app->delete('{id}', 'TodoController@delete');
});
