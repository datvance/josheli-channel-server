<?php

namespace App\Channels;

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
 * @property $channel
 *
 * @package App\Channels
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
     * String id, e.g. "main-menu"
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
     * @var Channel
     */
    'channel' => null,
  ];

  public function __construct()
  {
    $this->initializeProperties();
  }

  protected function initializeProperties()
  {
    //extend this class's properties with sub-class properties
    $class = get_called_class();

    //handle the case where sub-class sets property directly
    $vars = get_class_vars($class);
    foreach($this->properties as $prop => $val)
    {
      if(isset($vars[$prop])) $this->properties[$prop] = $vars[$prop];
    }

    //add any additional props to $properties array
    while ($class = get_parent_class($class))
    {
      $this->properties += get_class_vars($class)['properties'];
    }

    //now, try to set some defaults
    foreach($this->properties as $prop => $val)
    {
      if($val === null && method_exists($this, $prop))
      {
        $this->$prop();
      }
    }
  }

  public function __get($name)
  {
    if(isset($this->properties[$name]))
    {
      if(method_exists($this, $name))
      {
        return $this->$name();
      }

      return $this->properties[$name];
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
    $props = $this->properties;
    unset($props['channel']);
    return array_filter($props);
  }

  public function type()
  {
    if(!$this->properties['type'])
    {
      if($this instanceof Directory)
      {
        $this->properties['type'] = 'directory';
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
      if($id != $this->type)
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
    if(!$this->properties['thumb'] && $this->channel())
    {
      $this->properties['thumb'] = route('asset', [
        'channel_name' => $this->channel()->id(),
        'asset_name' => $this->id.'.jpg'
      ]);
    }
    return $this->properties['thumb'];
  }

  /**
   * The channel that this item is a part of
   *
   * @return Channel
   */
  public function channel()
  {
    if(!$this->properties['channel'])
    {
      $parts = explode('\\', get_class($this));
      $namespace = join('\\', array_slice($parts, 0, 3));
      $ns_class = $namespace . '\\' . basename($parts[2]);
      if(class_exists($ns_class))
      {
        $this->properties['channel'] = new $ns_class();
      }
    }

    return $this->properties['channel'];
  }
}