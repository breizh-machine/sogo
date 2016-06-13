<?php

namespace Tests\Scubs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseRestTestController extends WebTestCase
{
    private $client;
    private $router;

    protected function assertJsonResponse($response, $statusCode = 200) {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    protected function getClient()
    {
        if (!$this->client) {
            $this->client = static::createClient();
            $container = $this->client->getContainer();
            $this->router = $container->get('router');
        }
        return $this->client;
    }

    protected function getRouter()
    {
        return $this->router;
    }
}
