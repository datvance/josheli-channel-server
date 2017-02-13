<?php

namespace App\Channels;

/**
 * Directories and Tracks are Items
 *
 * Class Item
 * @package App\Channels
 */
class Item
{
  /**
   * Either "directory" or "track"
   * @var string
   */
  protected $type = '';

  /**
   * String id, e.g. "main-menu"
   * @var string
   */
  protected $id = '';
  protected $title = '';
  protected $summary = '';
  protected $thumb = '';

  /**
   * The Channel this item is a part of
   * @var Channel
   */
  protected $channel;

  /**
   * An array of info about this item, basically most of the properties
   *
   * @return array
   */
  public function info()
  {
    return [
      'type' => $this->type(),
      'id' => $this->id(),
      'title' => $this->title(),
      'summary' => $this->summary(),
      'thumb' => $this->thumb()
    ];
  }

  public function type()
  {
    if(!$this->type)
    {
      if($this instanceof Directory)
      {
        $this->type = 'directory';
      }
      elseif($this instanceof Track)
      {
        $this->type = 'track';
      }
    }

    return $this->type;
  }

  public function title()
  {
    if(!$this->title)
    {
      $this->title = Helpers::deslugify($this->id());
    }

    return $this->title;
  }

  public function id()
  {
    if(!$this->id)
    {
      $class_name = class_basename($this);
      $this->id = Helpers::slugify($class_name);
    }

    return $this->id;
  }

  public function summary()
  {
    return $this->summary;
  }

  public function thumb()
  {
    if(!$this->thumb)
    {
      $this->thumb = route('asset', [
        'channel_name' => $this->channel()->id(),
        'asset_name' => $this->id().'.jpg'
      ]);
    }
    return $this->thumb;
  }

  /**
   * The channel that this item is a part of
   *
   * @return Channel
   */
  public function channel()
  {
    if(!$this->channel)
    {
      $parts = explode('\\', get_class($this));
      $namespace = join('\\', array_slice($parts, 0, 3));
      $ns_class = $namespace . '\\' . basename($parts[2]);
      $this->channel = new $ns_class();
    }

    return $this->channel;
  }
}