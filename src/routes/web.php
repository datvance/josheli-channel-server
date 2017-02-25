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
    $app->get('', [
      'as' => 'channel',
      'uses' => 'ChannelController@channel'
    ]);

    $app->get('directory/{directory_id}', [
      'as' => 'directory',
      'uses' => 'ChannelController@directory'
    ]);

    $app->get('asset/{asset_name}', [
      'as' => 'asset',
      'uses' => 'ChannelController@asset'
    ]);


    /**
     * Scan and load optional, dynamic routes in each channel
     *
     * @var \Josheli\Core\Channel $channel
     */
    foreach(\Josheli\Core\Helpers::getChannels($objects = true) as $channel)
    {
        $channel_routes = base_path('app/Josheli/Channels/'.$channel->className().'/routes.php');
        if(file_exists($channel_routes))
        {
            $app->group(
              ['namespace' => '\Josheli\Channels\\'.$channel->className()],
              function() use ($app, $channel, $channel_routes) {
                include $channel_routes;
            });
        }
    }
});