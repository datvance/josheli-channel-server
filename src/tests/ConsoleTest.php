<?php

use \Josheli\Core\Helpers;
use \Josheli\Core\Channel;

class ConsoleTest extends TestCase
{
  protected $channel_name = 'Console Test';

  public function tearDown()
  {
    parent::tearDown();

    $channel_path = $this->channelPath();

    if(file_exists($channel_path))
    {
      shell_exec("rm -rf $channel_path");
    }
  }

  protected function channelPath()
  {
    return base_path('app/Josheli/Channels/' . studly_case($this->channel_name));
  }

  /**
   * @group josheli-console
   */
  public function testMakeChannel()
  {
    $command = 'php ' . base_path() . '/artisan make:channel "'.$this->channel_name.'"';

    shell_exec($command);

    $channel = Helpers::channel(studly_case($this->channel_name));
    $this->assertTrue($channel instanceof Channel);

    $channel_path = $this->channelPath();

    foreach(['assets', 'Directories', 'Tracks'] as $directory)
    {
      $new_directory = $channel_path . '/' . $directory;
      $this->assertTrue(file_exists($new_directory));
    }
  }
}