<?php

namespace Josheli\Core;

/**
 * An Audio or Video Track representation
 *
 * Class Track
 *
 * @property $container
 * @property $url
 * @property $date
 *
 * @package Josheli\Core
 */
class Track extends Item
{
  protected $properties = [

    /**
     * Audio container, one of "mp3", "mp4", "mov", "avi", "flv"
     * @var string
     */

    'container' => null,

    /**
     * The URL to the audio file
     *
     * @var string
     */

    'url' => null,

    /**
     * Unix Timestamp of this audio track
     * @var int
     */

    'date' => null,
  ];

  /**
   * @return array
   */
  public function info()
  {
    $info = parent::info();

    $info['url'] = $this->properties['url'];

    if($this->properties['container'])
    {
      $info['container'] = $this->properties['container'];
    }

    $info['date'] = $this->date();

    return $info;
  }
  
  public function date()
  {
    return $this->properties['date'] ? $this->properties['date'] : time();
  }
}