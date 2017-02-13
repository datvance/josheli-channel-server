<?php

namespace App\Channels;

/**
 * A collection of items: either other directories or tracks
 *
 * Class Directory
 * @package App\Channels
 */
class Directory extends Item
{
  /**
   * A list of the items in this directory
   * 
   * @return array
   */
  public function items()
  {
    return [];
  }
}