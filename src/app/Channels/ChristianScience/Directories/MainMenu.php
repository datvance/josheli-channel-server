<?php

namespace App\Channels\ChristianScience\Directories;

use App\Channels\ChristianScience\Tracks\ScienceAndHealth;
use App\Channels\ChristianScience\Tracks\SundayService;
use App\Channels\ChristianScience\Tracks\WednesdayService;
use App\Channels\Directory;

class MainMenu extends Directory
{
  protected $title = "Christian Science";
  
  public function items()
  {
    $items = [
      (new DailyLift())->info(),
      (new SentinelWatch())->info(),
      (new ScienceAndHealth())->info(),
      (new SundayService())->info(),
    ];

    $wed = (new WednesdayService())->info();
    if($wed['url'])
    {
      $items[] = $wed;
    }

    return $items;
  }
}