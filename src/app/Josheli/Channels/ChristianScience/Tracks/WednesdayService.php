<?php

namespace Josheli\Channels\ChristianScience\Tracks;

use Josheli\Core\Track;

class WednesdayService extends Track
{
  protected $container = 'mp3';
  protected $title = 'Online Wednesday Service';

  public function __construct()
  {
    parent::__construct();

    /**
     * Recorded version of service goes up around 3pm eastern
     * And taken down 8am easter on Friday
     */
    list($day, $hour) = explode('-', date('l-H'));

    $wednesday_service_time = 0;

    if($day == 'Wednesday' && intval($hour) >= 15)
    {
      $wednesday_service_time = strtotime('today');
    }
    elseif($day == 'Thursday' || ($day == 'Friday' && intval($hour) < 6))
    {
      $wednesday_service_time = strtotime('last Wednesday');
    }

    if($wednesday_service_time)
    {
      $wednesday_service_file = date('ymd', $wednesday_service_time);
      $wednesday_service_date = date('F j, Y', $wednesday_service_time);

      $this->url = 'http://dl.cdn.csps.com/clerk/wed_service/wed_service_'.$wednesday_service_file.'.mp3';
      
      $this->summary = 'Wednesday Service from The Mother Church in Boston on '.$wednesday_service_date.'.';

      $this->date = $wednesday_service_time;
    }

  }
}