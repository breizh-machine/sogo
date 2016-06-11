<?php

namespace Test\Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomain\Core\Resource as BaseResource;
use Scubs\CoreDomainBundle\Repository\InMemoryResourceRepository;
use Scubs\CoreDomain\Core\ResourceId;

class InMemoryResourceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $repository;

    public function testAdd()
    {
        $this->initRepository();
        $this->repository->add(new BaseResource(new ResourceId(null)));
        $all = $this->repository->findAll();
        $this->assertEquals(1, count($all));
    }

    public function testFind()
    {
        $this->initRepository();
        $r1 = new BaseResource(new ResourceId('ID'));
        $r2 = new BaseResource(new ResourceId('ID1'));
        $r3 = new BaseResource(new ResourceId('ID2'));
        $this->repository->add($r1);
        $this->repository->add($r2);
        $this->repository->add($r3);
        $foundById = $this->repository->find(new ResourceId('ID1'));

        $this->assertNotNull($foundById);
        $this->assertTrue($foundById->equals($r2));
        $this->assertFalse($foundById->equals($r1));
    }

    public function testRemove()
    {
        $this->initRepository();
        $r1 = new BaseResource(new ResourceId('ID'));
        $r2 = new BaseResource(new ResourceId('ID1'));
        $r3 = new BaseResource(new ResourceId('ID2'));
        $this->repository->add($r1);
        $this->repository->add($r2);
        $this->repository->add($r3);
        $this->repository->remove($r2);
        $all = $this->repository->findAll();
        $foundById = $this->repository->find(new ResourceId('ID1'));

        $this->assertEquals(2, count($all));
        $this->assertTrue($foundById == null);
    }

    public function testExists()
    {
        $this->initRepository();
        $r1 = new BaseResource(new ResourceId('ID'));
        $r2 = new BaseResource(new ResourceId('ID1'));
        $r3 = new BaseResource(new ResourceId('ID2'));
        $this->repository->add($r1);
        $this->repository->add($r2);
        $this->repository->add($r3);

        $this->assertTrue($this->repository->exists($r2));
        $this->assertFalse($this->repository->exists(new BaseResource(new ResourceId('nonexistingid'))));
        $this->assertTrue($this->repository->exists(new BaseResource(new ResourceId('ID2'))));
    }

    private function initRepository()
    {
        $this->repository = new InMemoryResourceRepository('Scubs\CoreDomain\Core\Resource', 'Scubs\CoreDomain\Core\ResourceId');
    }

}