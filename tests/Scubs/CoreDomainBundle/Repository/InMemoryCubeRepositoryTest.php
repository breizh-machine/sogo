<?php

namespace Test\Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomainBundle\Repository\InMemoryCubeRepository;
use Scubs\CoreDomain\Cube\CubeId;

class InMemoryCubeRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $repository = new InMemoryCubeRepository();
        $repository->add(new Cube(new CubeId(null), 'test', 0, 'test'));
        $all = $repository->findAll();
        $this->assertTrue( count($all) > 0);
    }
}