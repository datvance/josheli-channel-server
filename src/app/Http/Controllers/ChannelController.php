<?php

namespace App\Http\Controllers;

use Josheli\Core\Directory;
use Josheli\Core\Helpers;
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
  public function channel($channel_id)
  {
    $response = [];
    
    try
    {
      $channel = Helpers::channel($channel_id);
      $response = $channel->mainMenu();
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
    $directory = null;

    if($directory_id == 'main-menu')
    {
      return redirect()->route('channel', ['channel_id' => $channel_id]);
    }

    try
    {
      /**
       * First see if the directory is a method on the channel class
       * and if so, call that. In this case, this method should return
       * an instance of a Directory
       */
      $channel = Helpers::channel($channel_id);
      $channel_method = camel_case($directory_id);

      if(method_exists($channel, $channel_method))
      {
        $directory = $channel->$channel_method();
      }
      else
      {
        /**
         * Next, see if a Directory class of this name exists in this Channel Namespace
         * and if so, call that. JSON should be automatically formatted in this case.
         */
        $ns_directory = $channel->channelNamespace() . '\\Directories\\' . studly_case($directory_id);

        /** @var Directory $directory */
        $directory = new $ns_directory();

        if($this->request->input('cache') == 'delete')
        {
          $directory->clearCache($directory_id);
        }
      }
    }
    catch (\Exception $e)
    {
      abort(404, $e->getMessage());
    }

    $response = [];
    if($directory)
    {
      $response = $directory->info();
      $response['items'] = $directory->items();
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
      base_path() . '/app/Josheli/Channels/' . camel_case($channel_id) . '/assets/' . $asset_name,
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
