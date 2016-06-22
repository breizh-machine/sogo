<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;

class OrmCubeRepositoryTest extends BaseOrmRepository
{
    public function setUp()
    {
        $this->init();
    }

    public function testAdd()
    {

        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($cube);

        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->beginTransaction();
        $this->cubeRepository->add($cube2);
        $this->cubeRepository->commit();

        $this->cubeRepository->remove($cube);
        $this->cubeRepository->remove($cube2);
    }

    public function testFind()
    {
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($cube);
        $cubeRetrieved = $this->cubeRepository->find($cube->getId());
        $this->assertTrue($cubeRetrieved->equals($cube));
        $this->cubeRepository->remove($cube);
    }

    public function testFindAll()
    {
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $countAll = count($this->cubeRepository->findAll());
        $this->cubeRepository->add($cube);
        $this->cubeRepository->add($cube2);
        $all = $this->cubeRepository->findAll();
        $this->assertTrue(count($all) == $countAll + 2);
        $this->cubeRepository->remove($cube);
        $this->cubeRepository->remove($cube2);
    }

    public function testRemove()
    {
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($cube);
        $this->cubeRepository->remove($cube);
        $this->assertTrue(!$this->cubeRepository->exists($cube));
        $this->cubeRepository->remove($cube);
    }

    public function testUpdate()
    {
        $cube = new Cube(new CubeId('cube'), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($cube);
        $cube = $this->cubeRepository->find(new CubeId('cube'));
        $this->cubeRepository->update($cube);
        $allCubes = $this->cubeRepository->findAll();
        $this->assertTrue(count($allCubes) == 1);
        $this->cubeRepository->remove($cube);
    }

    public function testGetCubesByRarity()
    {
        $this->init('cube_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 1, 'description');
        $cube3 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($cube);
        $this->cubeRepository->add($cube2);
        $this->cubeRepository->add($cube3);
        $this->assertTrue(count($this->cubeRepository->getCubesByRarity(0)) == 2);
        $this->assertTrue(count($this->cubeRepository->getCubesByRarity(1)) == 1);
        $this->assertTrue(count($this->cubeRepository->getCubesByRarity(3)) == 0);
        $this->cubeRepository->remove($cube);
        $this->cubeRepository->remove($cube2);
        $this->cubeRepository->remove($cube3);
    }
}