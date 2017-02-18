<?php

namespace App\Channels\ChristianScience\Tracks;

use App\Channels\Track;

class SundayService extends Track
{
  protected $container = 'mp3';
  protected $title = 'Online Sunday Service';
  
  public function __construct()
  {
    parent::__construct();
    
    /**
     * Recorded version of service goes up around noon eastern
     */
    list($day, $hour) = explode('-', date('l-H'));

    if($day == 'Sunday' && $hour >= 12)
    {
      $sunday_service_time = strtotime('today');
    }
    else
    {
      $sunday_service_time = strtotime('last Sunday');
    }

    $sunday_service_file = date('ymd', $sunday_service_time);
    $sunday_service_date = date('F j, Y', $sunday_service_time);

    $this->url = 'http://dl.cdn.csps.com/clerk/church_service/church_service_'.$sunday_service_file.'.mp3';
    
    $this->summary = 'Sunday Service from The Mother Church in Boston on '.$sunday_service_date.'.';
    
    $this->date = $sunday_service_time;
    
  }
}