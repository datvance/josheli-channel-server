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

$app->group(['prefix' => 'channel/{channel_name}'], function () use ($app)
{
    $app->get('', [
      'as' => 'index',
      'uses' => 'ChannelController@index'
    ]);
    
    $id = 'directory';
    $app->get($id.'/{directory_id}', [
      'as' => $id,
      'uses' => 'ChannelController@'.camel_case($id)
    ]);

    $id = 'asset';
    $app->get($id.'/{asset_name}', [
      'as' => $id,
      'uses' => 'ChannelController@'.camel_case($id)
    ]);

});