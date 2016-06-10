<?php

namespace Test\Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomainBundle\Repository\InMemoryCubeRepository;

class InMemoryCubeRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $repository = new InMemoryCubeRepository();
        $all = $repository->findAll();
        $this->assertEquals(2, count($all));
    }
}