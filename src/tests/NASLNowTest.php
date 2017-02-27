<?php

/**
 * Class NASLNowTest
 *
 * @group nasl-now
 */
class NASLNowTest extends TestCase
{
  protected $channel_id = 'nasl-now';

  public function testChannel()
  {
    $this->endpointContainsJson('', [
      'endpoint' => "/channel/{$this->channel_id}",
      'type' => "channel",
      'id' => $this->channel_id,
      'title' => 'NASL Now',
      'channel_id' => $this->channel_id
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('background', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/background.jpg', $json['background']);
    $this->assertArrayHasKey('thumb', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/thumb.jpg', $json['thumb']);

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $types = ['directory'];
    $ids = ['official', 'latest'];

    foreach($json['items'] as $item)
    {
      $this->assertArrayHasKey('type', $item);
      $this->assertTrue(in_array($item['type'], $types));

      $this->assertArrayHasKey('id', $item);
      $this->assertTrue(in_array($item['id'], $ids));

    }
  }

  public function testOfficial()
  {
    $this->endpointContainsJson('/directory/official', [
      'type' => 'directory',
      'id' => 'official',
      'title' => 'NASL Official',
      'endpoint' => "/channel/{$this->channel_id}/directory/official",
      'channel_id' => $this->channel_id,
    ]);
  }

  public function testLatest()
  {
    $this->endpointContainsJson('/directory/latest', [
      'type' => 'directory',
      'id' => 'latest',
      'title' => 'Latest',
      'endpoint' => "/channel/{$this->channel_id}/directory/latest",
      'channel_id' => $this->channel_id,
    ]);
  }
}