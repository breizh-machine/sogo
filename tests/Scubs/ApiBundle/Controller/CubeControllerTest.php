<?php

namespace Tests\Scubs\ApiBundle\Controller;

class CubeControllerTest extends BaseRestTestController
{
    public function testAllAction()
    {
        $client = $this->getClient();
        $router = $this->getRouter();

        $route = $router->generate('scubs_api.cube_all', ['_format' => 'json']);
        $client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $parsedContent = json_decode($content);
        $this->assertTrue(count($parsedContent) == 0);
    }
}
