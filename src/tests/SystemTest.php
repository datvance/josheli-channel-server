<?php

class SystemTest extends TestCase
{
  protected $channel_id = 'system';

  public function testChannel()
  {
    $this->endpointContainsJson('', [
      'endpoint' => "/channel/{$this->channel_id}",
      'type' => "channel",
      'id' => $this->channel_id,
      'title' => "Josheli TV",
      'channel_id' => $this->channel_id
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('background', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/background.jpg', $json['background']);
    $this->assertArrayHasKey('thumb', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/thumb.jpg', $json['thumb']);

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $types = ['channel'];

    $ids = [];
    foreach(\App\Channels\Helpers::getChannels() as $directory)
    {
      $ids[] = \App\Channels\Helpers::slugify(basename($directory));
    }

    foreach($json['items'] as $item)
    {
      $this->assertArrayHasKey('type', $item);
      $this->assertTrue(in_array($item['type'], $types));

      if($item['type'] == 'track')
      {
        $this->assertEquals('mp3', $item['container']);
      }

      $this->assertArrayHasKey('id', $item);
      $this->assertTrue(in_array($item['id'], $ids));

    }
  }
}