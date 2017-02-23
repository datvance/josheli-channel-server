<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'channel/{channel_id}'], function () use ($app)
{
    $routes = [
        'main-menu' => '',
        'directory' => '/{directory_id}',
        'asset' => '/{asset_name}'
    ];
    
    foreach($routes as $id => $route)
    {
        if($route) $route = $id . $route;

        $app->get($route, [
          'as' => $id,
          'uses' => 'ChannelController@'.camel_case($id)
        ]);
    }

});