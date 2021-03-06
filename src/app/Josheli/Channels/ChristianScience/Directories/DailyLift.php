<?php

namespace Josheli\Channels\ChristianScience\Directories;

use Josheli\Core\Directory;
use Josheli\Core\Track;
use PicoFeed\Reader\Reader;

class DailyLift extends Directory
{
  protected $summary = 'Short podcasts to inspire.';

  /**
   * Handle the directory/daily-lift endpoint
   * @return array
   */
  public function items()
  {
    $lifts = $this->getCache(__FUNCTION__, []);

    if($lifts) return $lifts;

    try {

      $reader = new Reader;
      $resource = $reader->download('https://www.christianscience.com/lectures/daily-lift/feed');

      $parser = $reader->getParser(
        $resource->getUrl(),
        $resource->getContent(),
        $resource->getEncoding()
      );

      $feed = $parser->execute();

      foreach ($feed->getItems() as $item)
      {
        $dt = $item->getPublishedDate();
        $timestamp = $dt->getTimestamp();

        $content = $item->getContent();

        $thumb = '';
        preg_match('@src="(.*?)"@', $content, $match);
        if(isset($match[1]) && !empty($match[1]))
        {
          $thumb = $match[1];
        }

        $day = $dt->format('F j, Y');

        $lift = new Track();
        $lift->url = $item->getEnclosureUrl();
        $lift->title = $item->getTitle();
        $lift->summary = $day . ': ' . trim(strip_tags($content));
        $lift->thumb = $thumb;
        $lift->date = $dt->getTimestamp();

        $lifts[$timestamp] = $lift->info();
      }

      sort($lifts, SORT_NUMERIC|SORT_ASC);
    }
    catch (\Exception $e)
    {
    }

    if($lifts)
    {
      $lifts = array_values($lifts);

      $this->putCache(__FUNCTION__, $lifts, 360);
    }

    return $lifts;
  }
}