<?php

namespace App\Channels\System;

use App\Channels\Channel;
use App\Channels\Helpers;

class System extends Channel
{
  protected $title = 'Josheli TV';
  protected $summary = 'Personal Channel Provider, i.e. Channels I Like';

  public function items()
  {
    /** @var Channel $channel */
    foreach(Helpers::getChannels($objects = true) as $channel)
    {
      if($channel->id() == $this->channel_id()) continue;
      $this->addItem($channel);
    }

    return parent::items();
  }
}