<?php

namespace App\Channels;

/**
 * An Audio Track representation
 *
 * Class Track
 * @package App\Channels
 */
class Track extends Item
{
  /**
   * Audio container, one of "mp3", "mp4", "mov", "avi", "flv"
   * @var string
   */
  protected $container = '';

  /**
   * The URL to the audio file
   *
   * @var string
   */
  protected $url = '';

  /**
   * Unix Timestamp of this audio track
   * @var int
   */
  protected $date = 0;

  /**
   * @return array
   */
  public function info()
  {
    $info = parent::info();

    $info['url'] = $this->url;

    if($this->container) 
    {
      $info['container'] = $this->container;
    }

    $info['date'] = $this->date ? $this->date : time();

    return $info;
  }
}