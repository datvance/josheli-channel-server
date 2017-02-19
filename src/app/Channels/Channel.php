<?php

namespace App\Channels;

class Channel extends Directory
{
  protected $properties = [
    'background' => null
  ];

  /**
   * The items from the Directory Index
   *
   * @return array
   */
  public function items()
  {
    $ns_class = 'App\\Channels\\' . studly_case($this->channel_id) . '\\Directories\Index';

    if(class_exists($ns_class))
    {
      /** @var Directory $index */
      $index = new $ns_class();

      return $index->items();
    }

    return [];
  }

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