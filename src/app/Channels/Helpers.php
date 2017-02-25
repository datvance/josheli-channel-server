<?php

namespace App\Channels;

use Illuminate\Filesystem\Filesystem;

class Helpers
{
  public static function slugify($string)
  {
    $string = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^\-])([A-Z][a-z])/'], '$1-$2', $string));
    return str_replace(' ', '', $string);
  }

  public static function deslugify($string)
  {
    return ucwords(str_replace('-', ' ', $string));
  }

  public static function getChannels($objects = false)
  {
    $fs = new Filesystem();
    $directories = $fs->directories(base_path('app/Channels'));
    if(!$objects) return $directories;

    $channels = [];
    foreach($directories as $directory)
    {
      $channels[] = Helpers::channel(basename($directory));
    }

    return $channels;
  }

  /**
   * Channel factory
   *
   * @param $channel_id_or_name "channel-id" or "ChannelName"
   * @return Channel
   * @throws \Exception
   */
  public static function channel($channel_id_or_name)
  {
    //ids are always all lower case
    if(strtolower($channel_id_or_name) === $channel_id_or_name)
    {
      $channel_class = studly_case($channel_id_or_name);
    }
    else
    {
      $channel_class = $channel_id_or_name;
    }

    $ns_channel = 'App\\Channels\\' . $channel_class . '\\' . $channel_class;

    if(class_exists($ns_channel))
    {
      return new $ns_channel();
    }

    throw new \Exception('Channel not found.');

  }
}