<?php

namespace App\Channels\System\Directories;

use App\Channels\Directory;
use App\Channels\Channel;
use App\Channels\Helpers;
use Illuminate\Filesystem\Filesystem;

class MainMenu extends Directory
{
  protected $title = 'Josheli TV';

  public function items()
  {
    $channels = [];
    $fs = new Filesystem();
    foreach($fs->directories(base_path('app/Channels')) as $directory)
    {
      $channel_name = basename($directory);
      if($channel_name == Helpers::deslugify($this->channel_id)) continue;

      $ns_class = 'App\Channels\\' . $channel_name . '\\' . $channel_name;
      /** @var Channel $channel */
      $channel = new $ns_class();

      $channels[] = $channel->info();
    }

    return $channels;
    
  }
}