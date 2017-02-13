<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class ChannelController
 * @package App\Http\Controllers
 */
class ChannelController extends Controller
{
  /**
   * @var \Illuminate\Http\Request
   */
  protected $request;

  /**
   * ChannelController constructor.
   * @param \Illuminate\Http\Request $request
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * @param $channel_name
   * @return \Illuminate\Http\JsonResponse
   */
  public function index($channel_name)
  {
    return $this->respond(
      $this->channel($channel_name)->info()
    );
  }

  /**
   * @param $channel_name
   * @param $directory_name
   * @return \Illuminate\Http\JsonResponse
   */
  public function directory($channel_name, $directory_name)
  {
    $channel = $this->channel($channel_name);
    
    if($this->request->input('cache') == 'delete')
    {
      $channel->clearCache($directory_name);
    }

    return $this->respond(
      $channel->directory($directory_name)
    );
  }

  /**
   * @param $channel_name
   * @param $asset_name
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   */
  public function asset($channel_name, $asset_name)
  {
    return response()->download(
      base_path() . '/app/Channels/' . camel_case($channel_name) . '/assets/' . $asset_name,
      $asset_name,
      [],
      'inline'
    );
  }

  /**
   * @param $channel_name
   * @return \App\Channels\Channel
   */
  protected function channel($channel_name)
  {
    $class_name = camel_case($channel_name);
    $ns_class = 'App\Channels\\'.$class_name.'\\'.$class_name;
    try
    {
      return new $ns_class();
    }
    catch (\Exception $e)
    {
      abort(404, $e->getMessage());
    }
  }

  /**
   * @param $payload
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respond($payload)
  {
    return response()->json($payload);
  }

}