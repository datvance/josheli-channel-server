<?php

namespace Josheli\Channels\ChristianScience;

use Josheli\Core\Channel;
use Josheli\Channels\ChristianScience\Directories\DailyLift;
use Josheli\Channels\ChristianScience\Directories\SentinelWatch;
use Josheli\Channels\ChristianScience\Tracks\ScienceAndHealth;
use Josheli\Channels\ChristianScience\Tracks\SundayService;
use Josheli\Channels\ChristianScience\Tracks\WednesdayService;

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