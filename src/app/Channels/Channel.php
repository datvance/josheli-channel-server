<?php

namespace App\Channels;

class Channel extends Directory
{
  protected $properties = [
    'background' => null
  ];

  public function directory($directory_id)
  {
    $thing = $this->getCache($directory_id);

    if($thing)
    {
      return $thing;
    }

    $contents = [
      'items' => [],
    ];

    /**
     * First, see if a class of type Directory, for this ID, exists in this Channel
     * e.g. 'main-menu' => App\Channels\ChannelId\Directories\MainMenu()
     */
    $ns_class =
      __NAMESPACE__ . '\\' .
      studly_case($this->id()) .
      '\\Directories\\' .
      studly_case($directory_id);
    
    if(class_exists($ns_class))
    {
      /** @var Directory $directory */
      $directory = new $ns_class();

      $contents = $directory->info() + $contents;
      $contents['items'] = $directory->items();
    }
    else
    {
      /**
       * Next, see if the channel has a method of this name
       * If so, the method is responsible for the response structure
       * e.g. 'main-menu' => $this->mainMenu()
       */
      $method = camel_case($directory_id);
      if(method_exists($this, $method))
      {
        $contents = $this->$method() + $contents;
      }
    }

    if($contents['items'])
    {
      $this->putCache($directory_id, $contents);
    }

    return $contents;
  }

  public function background()
  {
    if(!$this->properties['background'])
    {
      $this->properties['background'] = route('asset', [
        'channel_name' => $this->id,
        'asset_name' => 'background.jpg'
      ]);
    }

    return $this->properties['background'];
  }

}