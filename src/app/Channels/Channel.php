<?php

namespace App\Channels;

/**
 * Class Channel
 *
 * @property $background
 *
 * @package App\Channels
 */
class Channel extends Directory
{
  protected $properties = [
    'background' => null
  ];

  /**
   * Answers the channel/channel_id endpoint
   * @return array
   */
  public function mainMenu()
  {
    $menu = $this->info();
    $menu['items'] = $this->items();

    return $menu;
  }

  /**
   * A background image for this channel
   *
   * @return mixed
   */
  public function background()
  {
    if(!$this->properties['background'])
    {
      $this->properties['background'] = route('asset', [
        'channel_id' => $this->id(),
        'asset_name' => 'background.jpg'
      ]);
    }

    return $this->properties['background'];
  }

  public function endpoint()
  {
    if(!$this->properties['endpoint'])
    {
      $url = route('channel', ['channel_id' => $this->channel_id()]);
      $this->properties['endpoint'] = parse_url($url, PHP_URL_PATH);
    }
    
    return $this->properties['endpoint'];
  }
}