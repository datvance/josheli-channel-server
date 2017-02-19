<?php


class ChristianScienceTest extends TestCase
{
  protected $channel_id = 'christian-science';

  public function testIndex()
  {
    $this->endpointContainsJson('', [
      'endpoint' => "/channel/christian-science",
      'type' => "channel",
      'id' => "christian-science",
      'title' => "Christian Science",
      'channel_id' => "christian-science"
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('background', $json);
    $this->assertStringEndsWith('/channel/christian-science/asset/background.jpg', $json['background']);
    $this->assertArrayHasKey('thumb', $json);
    $this->assertStringEndsWith('/channel/christian-science/asset/thumb.jpg', $json['thumb']);

  }

  /**
   * Test the Main Menu directory.
   *
   * @return void
   */
  public function testMainMenu()
  {
    $this->endpointContainsJson('/directory/main-menu', [
      'type' => 'directory',
      'id' => 'main-menu',
      'title' => 'Christian Science',
      'endpoint' => "/channel/{$this->channel_id}/directory/main-menu"
    ]);

    $json = $this->getJsonAsArray();

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $types = ['directory', 'track', 'video'];
    $ids = ['daily-lift', 'sentinel-watch', 'science-and-health', 'sunday-service'];

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