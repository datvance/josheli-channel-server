<?php

namespace App\Channels;

abstract class Channel
{
  /**
   * channel-id used in urls, etc
   * @var
   */
  protected $id;

  /**
   * "Channel Name" for humans
   * @var
   */
  protected $name;

  /**
   * Defaults to "background.png"
   * @var
   */
  protected $background_image;

  /**
   * Defaults to "icon.png"
   * @var
   */
  protected $icon;

  /**
   * Plex view type (or something)
   * @var string
   */
  protected $view_group = 'List';

  /**
   * @var \Illuminate\Contracts\Cache\Repository
   */
  protected $cache;

  public function __construct()
  {
    $this->cache = app()->make('cache');
  }

  public function info()
  {
    return [
      'name' => $this->name(),
      'background-image' => $this->backgroundImage(),
      'channel-icon' => $this->icon(),
      'view-group' => $this->viewGroup()
    ];
  }

  public function directory($directory_id)
  {
    $thing = $this->getCache($directory_id);

    if($thing)
    {
      return $thing;
    }

    $contents = [
      'endpoint' => '/channel/' . $this->id() . '/directory/' . $directory_id,
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

  public function name()
  {
    if(!$this->name)
    {
      $this->name = Helpers::deslugify($this->id());
    }

    return $this->name;
  }

  public function id()
  {
    if(!$this->id)
    {
      $namespaced_class = get_class($this);
      $class_name = substr($namespaced_class, strrpos($namespaced_class, '\\') + 1);

      $this->id = Helpers::slugify($class_name);
    }

    return $this->id;
  }

  public function backgroundImage()
  {
    $channel_id = $this->id();

    if(!$this->background_image)
    {
      $this->background_image = "/public/channels/{$channel_id}/background.png";
    }

    return $this->background_image;
  }

  public function icon()
  {
    $channel_id = $this->id();

    if(!$this->icon)
    {
      $this->channel_icon = "/public/channels/{$channel_id}/icon.png";
    }

    return $this->icon;
  }

  public function viewGroup()
  {
    return $this->view_group;
  }

  public function getCache($cache_name)
  {
    $cache_name = $this->id() . '-' . $cache_name;

    return $this->cache->get($cache_name);
  }

  public function putCache($cache_name, $value, $minutes = 60)
  {
    $cache_name = $this->id() . '-' . $cache_name;

    return $this->cache->add($cache_name, $value, $minutes);
  }

  public function clearCache($cache_name)
  {
    $cache_name = $this->id() . '-' . $cache_name;

    return $this->cache->forget($cache_name);
  }

}