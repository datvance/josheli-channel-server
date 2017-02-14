<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeChannel extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'make:channel {channel : The name of your Channel, surrounded by quotes if it contains spaces}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate a Channel skeleton';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $fs = new Filesystem();

    $given_name = $this->argument('channel');
    $dir_name = studly_case($given_name);

    $channel_path = base_path('app/Channels') . '/' . $dir_name;
    
    if($fs->exists($channel_path))
    {
      $this->error('Channel by that name already exists!');
      return false;
    }
    
    $fs->makeDirectory($channel_path);
    
    foreach(['assets', 'Directories', 'Tracks'] as $directory)
    {
      $new_directory = $channel_path . '/' . $directory;
      $fs->makeDirectory($new_directory);
      file_put_contents($new_directory . '/.gitkeep', '');
    }
    
    file_put_contents($channel_path . '/' . $dir_name . '.php', $this->channelClassTemplate($dir_name, $given_name));

    $this->info('New Channel, "' . $given_name . '", created at: ' . base_path('app/Channels/' . $dir_name));
    return true;
  }

  protected function channelClassTemplate($channel_name, $given_name)
  {
    return '<?php

namespace App\Channels\\'.$channel_name.';

use App\Channels\Channel;

class '.$channel_name.' extends Channel
{
  protected $description = \''.$given_name.'\';
}';
  }
}