<?php

namespace Josheli\Channels\NASLNow;

use Josheli\Core\Channel;
use Josheli\Core\Directory;
use Josheli\Core\Helpers;
use Josheli\Core\Video;
use Madcoda\Youtube\Youtube;

class NASLNow extends Channel
{
  protected $title = 'NASL Now';
  
  public function items()
  {
    $this->addItem($this->latest(false));
    $this->addItem($this->official(false));

    return parent::items();
  }

  public function official($items = true)
  {
    $official = new Directory();
    $official->title = 'NASL Official';
    $official->summary = 'Latest Videos from nasl.com';
    $official->id = __FUNCTION__;
    $official->channel_id = $this->channel_id();

    if($items)
    {
      $youtube = Helpers::youtube();
      $yt_channel = $youtube->getChannelByName('NASLOfficial');
      $upload_id = $yt_channel->contentDetails->relatedPlaylists->uploads;

      $videos = $youtube->getPlaylistItemsByPlaylistId($upload_id, 25);

      foreach($videos as $video)
      {
        $vid = new Video();
        $vid->id = 'youtube-' . $video->contentDetails->videoId;
        $vid->channel_id = $this->channel_id();
        $vid->title = $video->snippet->title;
        $vid->summary = strtok($video->snippet->description, '.');
        $vid->thumb = 'https://youtube.com';
        $vid->url = 'https://youtube.com/watch?v=' . $video->contentDetails->videoId;
        $vid->date = strtotime($video->contentDetails->videoPublishedAt);

        $official->addItem($vid);
      }
    }

    return $official;
  }

  public function latest($items = true)
  {
    $latest = new Directory();
    $latest->title = 'Latest Videos';
    $latest->summary = 'Latest Youtube Videos about NASL';
    $latest->id = __FUNCTION__;
    $latest->channel_id = $this->channel_id();

    if($items)
    {
      $youtube = Helpers::youtube();
      $videos = $youtube->searchVideos('NASL Soccer', 25, Youtube::ORDER_DATE);

      foreach($videos as $video)
      {
        $vid = new Video();
        $vid->id = 'youtube-' . $video->id->videoId;
        $vid->channel_id = $this->channel_id();
        $vid->title = $video->snippet->title;
        $vid->summary = strtok($video->snippet->description, '.');
        $vid->thumb = 'https://youtube.com';
        $vid->url = 'https://youtube.com/watch?v=' . $video->id->videoId;
        $vid->date = strtotime($video->snippet->publishedAt);

        $latest->addItem($vid);
      }
    }

    return $latest;
  }
}