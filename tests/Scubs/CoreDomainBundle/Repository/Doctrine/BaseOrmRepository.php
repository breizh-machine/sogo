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
    protected $repository;
    private $client;

    protected function init($repositoryName)
    {
        if (!$this->client) {
            $this->client = static::createClient();
            $container = $this->client->getContainer();
            $this->repository = $container->get($repositoryName);
        }
    }

    protected function cleanRepository()
    {
        $resources = $this->repository->findAll();
        foreach ($resources as $resource){
            $this->repository->remove($resource);
        }
    }
}
