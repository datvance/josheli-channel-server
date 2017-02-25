<?php

namespace Josheli\Core;

/**
 * A collection of items: either other directories or tracks
 *
 * Class Directory
 *
 * @property $endpoint
 *
 * @package Josheli\Core
 */
class Directory extends Item implements DirectoryInterface
{
  protected $items = [];

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
    return $this->items;
  }

  public function addItem(Item $item)
  {
    $this->items[] = $item->info();
  }

  /**
   * The API url path, e.g. "/channel/channel-id/directory/directory-id"
   * @return mixed
   */
  public function endpoint()
  {
    if(!$this->properties['endpoint'] && $this->channel_id())
    {
      $url = route('directory', [
        'channel_id' => $this->channel_id(),
        'directory_id' => $this->id()
      ]);

      $this->properties['endpoint'] = parse_url($url, PHP_URL_PATH);
    }

    return $this->properties['endpoint'];
  }  
}