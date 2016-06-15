<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;

class OrmCubeRepositoryTest extends BaseOrmRepository
{
    public function testAdd()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->repository->add($cube);

        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->repository->beginTransaction();
        $this->repository->add($cube2);
        $this->repository->commit();

        $this->repository->remove($cube);
        $this->repository->remove($cube2);
    }

    public function testFind()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->repository->add($cube);
        $cubeRetrieved = $this->repository->find($cube->getId());
        $this->assertTrue($cubeRetrieved->equals($cube));
        $this->repository->remove($cube);
    }

    public function testFindAll()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $countAll = count($this->repository->findAll());
        $this->repository->add($cube);
        $this->repository->add($cube2);
        $all = $this->repository->findAll();
        $this->assertTrue(count($all) == $countAll + 2);
        $this->repository->remove($cube);
        $this->repository->remove($cube2);
    }

    public function testRemove()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->repository->add($cube);
        $this->repository->remove($cube);
        $this->assertTrue(!$this->repository->exists($cube));
        $this->repository->remove($cube);
    }

    public function testGetCubesByRarity()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 1, 'description');
        $cube3 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->repository->add($cube);
        $this->repository->add($cube2);
        $this->repository->add($cube3);
        $this->assertTrue(count($this->repository->getCubesByRarity(0)) == 2);
        $this->assertTrue(count($this->repository->getCubesByRarity(1)) == 1);
        $this->assertTrue(count($this->repository->getCubesByRarity(3)) == 0);
        $this->repository->remove($cube);
        $this->repository->remove($cube2);
        $this->repository->remove($cube3);
    }
}