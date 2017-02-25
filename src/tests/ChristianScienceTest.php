<?php

use Josheli\Core\Helpers;

class ChristianScienceTest extends TestCase
{
  protected $channel_id = 'christian-science';

  public function testChannel()
  {
    $this->endpointContainsJson('', [
      'endpoint' => "/channel/{$this->channel_id}",
      'type' => "channel",
      'id' => $this->channel_id,
      'title' => Helpers::deslugify($this->channel_id),
      'channel_id' => $this->channel_id
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('background', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/background.jpg', $json['background']);
    $this->assertArrayHasKey('thumb', $json);
    $this->assertStringEndsWith('/channel/'.$this->channel_id.'/asset/thumb.jpg', $json['thumb']);

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $types = ['directory', 'track', 'video'];
    $ids = ['daily-lift', 'sentinel-watch', 'science-and-health', 'sunday-service', 'wednesday-service'];

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

  /**
   * Test the Directory Index.
   *
   * @return void
   */
  public function testMainMenuRedirectsToChannel()
  {
    $this->get('/channel/' . $this->channel_id . '/directory/main-menu');

    $this->assertResponseStatus(302);

  }

  public function testDailyLift()
  {
    $this->endpointContainsJson('/directory/daily-lift', [
      'type' => 'directory',
      'id' => 'daily-lift',
      'title' => 'Daily Lift',
      'summary' => 'Short podcasts to inspire.',
      'endpoint' => "/channel/{$this->channel_id}/directory/daily-lift"
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $keys = ['url', 'type', 'date', 'title', 'summary', 'thumb'];
    foreach($json['items'] as $item)
    {
      //make sure each lift has all these keys
      $this->assertEmpty(array_diff_key(array_flip($keys), $item));

      //each lift should be an mp3 track
      $this->assertEquals('track', $item['type']);
      $this->assertContains('.mp3', $item['url']);
    }

  }

  public function testSentinelWatch()
  {
    $this->endpointContainsJson('/directory/sentinel-watch', [
      'type' => 'directory',
      'id' => 'sentinel-watch',
      'title' => 'Sentinel Watch',
      'summary' => 'Weekly podcast from the Christian Science Sentinel.',
      'endpoint' => "/channel/{$this->channel_id}/directory/sentinel-watch"
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $keys = ['url', 'type', 'date', 'title', 'summary', 'thumb'];
    foreach($json['items'] as $item)
    {
      //make sure has all these keys
      $this->assertEmpty(array_diff_key(array_flip($keys), $item));

      $this->assertEquals('track', $item['type']);
      $this->assertContains('.mp3', $item['url']);
    }

  }

}