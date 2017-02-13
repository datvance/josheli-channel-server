<?php

namespace App\Channels;


class Helpers
{
  public static function slugify($string)
  {
    return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^\-])([A-Z][a-z])/'], '$1-$2', $string));
  }

  public static function deslugify($string)
  {
    return ucwords(str_replace('-', ' ', $string));
  }
}