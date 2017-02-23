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
   * The items from the Directory MainMenu
   *
   * @return array
   */
  public function items()
  {
    $ns_class = 'App\\Channels\\' . studly_case($this->channel_id) . '\\Directories\MainMenu';

    if(class_exists($ns_class))
    {
      /** @var Directory $main_menu */
      $main_menu = new $ns_class();

      return $main_menu->items();
    }

    return [];
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

}