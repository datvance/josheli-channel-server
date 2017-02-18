<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $channel_id = '';

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function endpointContainsJson($endpoint, $json)
    {
        $this->get('/channel/' . $this->channel_id . $endpoint)->seeJson($json);
    }

    public function getJsonAsArray()
    {
        return json_decode($this->response->getContent(), true);
    }
}
