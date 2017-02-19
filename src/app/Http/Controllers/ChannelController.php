<?php

namespace App\Http\Controllers;

use App\Channels\Channel;
use App\Channels\Directory;
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
   * @param $channel_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function index($channel_id)
  {
    $response = [];
    try
    {
      $class_name = camel_case($channel_id);
      $ns_class = 'App\Channels\\'.$class_name.'\\'.$class_name;

      /** @var Channel $channel */
      $channel = new $ns_class();
      $response = $channel->info();
      $response['items'] = $channel->items();
    }
    catch (\Exception $e)
    {
      abort(404, $e->getMessage());
    }

    return $this->respond($response);
  }

  /**
   * @param $channel_id
   * @param $directory_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function directory($channel_id, $directory_id)
  {
    if($directory_id == 'index')
    {
      return redirect()->route('index', ['channel_id' => $channel_id]);
    }

    $response = [];

    try
    {
      $ns_class =
        'App\\Channels\\' .
        studly_case($channel_id) .
        '\\Directories\\' .
        studly_case($directory_id);

      /** @var Directory $directory */
      $directory = new $ns_class();

      if($this->request->input('cache') == 'delete')
      {
        $directory->clearCache($directory_id);
      }

      $response = $directory->info();
      $response['items'] = $directory->items();

    }
    catch (\Exception $e)
    {
      abort(404, $e->getMessage());
    }

    return $this->respond($response);
  }

  /**
   * @param $channel_id
   * @param $asset_name
   * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
   */
  public function asset($channel_id, $asset_name)
  {
    return response()->download(
      base_path() . '/app/Channels/' . camel_case($channel_id) . '/assets/' . $asset_name,
      $asset_name,
      [],
      'inline'
    );
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
