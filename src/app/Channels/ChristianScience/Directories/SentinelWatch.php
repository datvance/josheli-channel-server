<?php

namespace App\Channels\ChristianScience\Directories;

use App\Channels\Directory;
use PicoFeed\Reader\Reader;

class SentinelWatch extends Directory
{
  protected $summary = 'Weekly podcast from the Christian Science Sentinel.';
  protected $thumb = 'https://sentinel.christianscience.com/var/sentinel/storage/images/sentinel-audio/sentinel-watch/9374738-18-eng-US/sentinel-watch_featureimage.jpg';

  /**
   * Handle the directory/sentinel-watch endpoint
   * @return array
   */
  public function items()
  {
    $items = [];

    try {

      $reader = new Reader;
      $resource = $reader->download('https://sentinel.christianscience.com/layout/set/rss/content/view/full/272173');

      $parser = $reader->getParser(
        $resource->getUrl(),
        $resource->getContent(),
        $resource->getEncoding()
      );

      $feed = $parser->execute();

      $thumb = $this->thumb;

      foreach ($feed->getItems() as $item)
      {
        //most of the shows require a login, only first one doesn't, usually
        $url = $item->getEnclosureUrl();
        if(!$url) continue;

        $dt = $item->getPublishedDate();
        $day = $dt->format('F j, Y');

        $podcast = [
          'type' => 'track',
          'url' => $url,
          'title' => $item->getTitle(),
          'summary' => $day . ': ' . $item->getContent(),
          'thumb' => $thumb,
          'date' => $dt->getTimestamp()
        ];

        $items[] = $podcast;
      }
    }
    catch (\Exception $e)
    {

    }

    return $items;
  }
}