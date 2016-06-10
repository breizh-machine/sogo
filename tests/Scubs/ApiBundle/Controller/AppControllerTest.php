<?php

namespace Tests\Scubs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Router;

class CubeControllerTest extends WebTestCase
{
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

    public function testAllAction()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $router = $container->get('router');

        $route = $router->generate('scubs_api.cube_all', ['_format' => 'json']);
        $client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        //$this->assertEquals($expected[$i], $content);
        dump($content);
    }
}
