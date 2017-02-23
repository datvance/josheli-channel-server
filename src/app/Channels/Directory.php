<?php

namespace App\Channels;

/**
 * A collection of items: either other directories or tracks
 *
 * Class Directory
 *
 * @property $endpoint
 *
 * @package App\Channels
 */
class Directory extends Item
{
  protected $properties = [
    /**
     *
     */
    'endpoint' => null,
  ];

  /**
   * A list of the items in this directory
   * 
   * @return array
   */
  public function items()
  {
    return [];
  }

  /**
   * The API url path, e.g. "/channel/channel-id/directory/directory-id"
   * @return mixed
   */
  public function endpoint()
  {
    if(!$this->properties['endpoint'] && $this->channel_id())
    {
      if($this instanceof Channel)
      {
        $url = route('main-menu', ['channel_id' => $this->channel_id()]);
      }
      else
      {
        $url = route('directory', [
          'channel_id' => $this->channel_id(),
          'directory_id' => $this->id()
        ]);
      }

      $this->properties['endpoint'] = parse_url($url, PHP_URL_PATH);
    }

    return $this->properties['endpoint'];
  }  
}