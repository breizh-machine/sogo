<?php
/**
 * Created by PhpStorm.
 * User: FMARTIN
 * Date: 15/06/2016
 * Time: 15:13
 */

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseOrmRepository extends WebTestCase
{
    protected $repositories;
    private $container;
    private $client;

    protected function init($repositoryName)
    {
        if (!$this->client) {
            $this->client = static::createClient();
            $this->container = $this->client->getContainer();
        }
        if (!isset($this->repositories[$repositoryName])) {
            $this->repositories[$repositoryName] = $this->container->get($repositoryName);
        }
    }

    protected function cleanRepository($repositoryName)
    {
        $resources = $this->repositories[$repositoryName]->findAll();
        foreach ($resources as $resource){
            $this->repositories[$repositoryName]->remove($resource);
        }
    }
}
