<?php

namespace App\Channels\ChristianScience;

use App\Channels\Channel;
use App\Channels\ChristianScience\Directories\DailyLift;
use App\Channels\ChristianScience\Directories\SentinelWatch;
use App\Channels\ChristianScience\Tracks\ScienceAndHealth;
use App\Channels\ChristianScience\Tracks\SundayService;
use App\Channels\ChristianScience\Tracks\WednesdayService;

class ChristianScience extends Channel
{
  protected $summary = 'Podcasts, Church Services and more.';

  public function items()
  {
    $this->addItem(new DailyLift());
    $this->addItem(new SentinelWatch());
    $this->addItem(new ScienceAndHealth());
    $this->addItem(new SundayService());

    $wed = new WednesdayService();
    $info = $wed->info();
    if($info['url'])
    {
      $this->addItem($wed);
    }

    return parent::items();
  }
}