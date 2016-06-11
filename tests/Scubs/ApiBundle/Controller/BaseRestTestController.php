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

    public function testAllAction()
    {
        $route = $router->generate('scubs_api.cube_all', ['_format' => 'json']);
        $client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $parsedContent = json_decode($content);
        $this->assertEquals(2, count($parsedContent->cubes));
    }
}
