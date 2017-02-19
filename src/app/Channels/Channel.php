<?php

namespace App\Channels;

class Channel extends Directory
{
  protected $properties = [
    'background' => null
  ];

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