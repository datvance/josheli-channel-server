<?php


class ChristianScienceTest extends TestCase
{
  protected $channel_id = 'christian-science';
  
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
      'title' => 'Christian Science'
    ]);

    $json = json_decode($this->response->getContent(), true);

    $this->assertArrayHasKey('items', $json);
    $this->assertNotEmpty($json['items']);

    $types = ['directory', 'track', 'video'];
    $ids = ['daily-lift', 'sentinel-watch', 'science-and-health', 'sunday-service'];

    foreach($json['items'] as $item)
    {
      $this->assertArrayHasKey('type', $item);
      $this->assertTrue(in_array($item['type'], $types));


      $this->assertArrayHasKey('id', $item);
      $this->assertTrue(in_array($item['id'], $ids));

    }

  }

}