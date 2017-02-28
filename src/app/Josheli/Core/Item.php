<?php

namespace Josheli\Core;

/**
 * Directories and Tracks are Items
 *
 * Class Item
 *
 * @property $type
 * @property $id
 * @property $title
 * @property $summary
 * @property $thumb
 * @property $channel_id
 *
 * @package Josheli\Core
 */
class Item
{
  protected $properties = [
    /**
     * Either "directory" or "track"
     * @var string
     */
    'type' => null,

    /**
     * String id, e.g. "daily-lift"
     * @var string
     */
    'id' => null,

    /**
     *
     */
    'title' => null,

    /**
     *
     */
    'summary' => null,

    /**
     *
     */
    'thumb' => null,

    /**
     * The Channel this item is a part of
     * @var string
     */
    'channel_id' => null,
  ];

  /**
   * @var \Illuminate\Contracts\Cache\Repository
   */
  protected $cache;

  public function __construct()
  {
    $this->cache = app()->make('cache');

    $this->initializeProperties();
  }

  protected function initializeProperties()
  {
    //extend this class's properties with sub-class properties
    $class = get_called_class();
    $called_class_vars = get_class_vars($class);

    //add any additional props to $properties array
    while ($class = get_parent_class($class))
    {
      $this->properties += get_class_vars($class)['properties'];
    }

    //handle the case where called class sets property directly
    foreach($this->properties as $prop => $val)
    {
      if(isset($called_class_vars[$prop])) $this->properties[$prop] = $called_class_vars[$prop];
    }
  }

  protected function computeProperties()
  {
    foreach($this->properties as $prop => $val)
    {
      if(method_exists($this, $prop))
      {
        $this->$prop();
      }
    }
  }

  public function __get($name)
  {
    if(isset($this->properties[$name]))
    {
      if(!empty($this->properties[$name]))
      {
        return $this->properties[$name];
      }

      if(method_exists($this, $name))
      {
        return $this->$name();
      }
    }

    return null;
  }

  public function __set($name, $value)
  {
    if(array_key_exists($name, $this->properties))
    {
      $this->properties[$name] = $value;
    }
  }

  /**
   * An array of info about this item, basically most of the properties
   *
   * @return array
   */
  public function info()
  {
    $this->computeProperties();
    return array_filter($this->properties);
  }

  public function type()
  {
    if(!$this->properties['type'])
    {
      if($this instanceof Channel)
      {
        $this->properties['type'] = 'channel';
      }
      elseif($this instanceof Directory)
      {
        $this->properties['type'] = 'directory';
      }
      elseif($this instanceof Video)
      {
        $this->properties['type'] = 'video';
      }
      elseif($this instanceof Track)
      {
        $this->properties['type'] = 'track';
      }
    }

    return $this->properties['type'];
  }

  public function title()
  {
    if(!$this->properties['title'])
    {
      $this->properties['title'] = Helpers::deslugify($this->id());
    }

    return $this->properties['title'];
  }

  public function id()
  {
    if(!$this->properties['id'])
    {
      $class_name = class_basename($this);
      $id = Helpers::slugify($class_name);
      //prevent the case where a track object is created wihtout being given an explicit id
      if($id != $this->type())
      {
        $this->properties['id'] = $id;
      }
    }

    return $this->properties['id'];
  }

  public function summary()
  {
    return $this->properties['summary'];
  }

  public function thumb()
  {
    if(!$this->properties['thumb'] && $this->channel_id())
    {
      $this->properties['thumb'] = route('asset', [
        'channel_id' => $this->channel_id(),
        'asset_name' => 'thumb.jpg'
      ]);
    }
    return $this->properties['thumb'];
  }

  /**
   * The channel that this item is a part of
   *
   * @return Channel
   */
  public function channel_id()
  {
    if(!$this->properties['channel_id'])
    {
      if($this instanceof Channel)
      {
        $this->properties['channel_id'] = $this->id();
      }
      else
      {
        $parts = explode('\\', get_class($this));
        $this->properties['channel_id'] = Helpers::slugify($parts[2]);
      }
    }

    return $this->properties['channel_id'];
  }

  public function className()
  {
    return class_basename($this);
  }

  public function channelNamespace()
  {
    return 'Josheli\Channels\\'.studly_case($this->channel_id());
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