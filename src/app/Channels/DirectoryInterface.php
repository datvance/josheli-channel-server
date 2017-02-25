<?php

namespace App\Channels;

interface DirectoryInterface {

  /**
   * The contents (videos, tracks, other directories) of the directory.
   * Channels are directories too, so should implement this as well
   *
   * @return array
   */
  public function items();

}