<?php

namespace Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Cube\CubeRepository;

class InMemoryCubeRepository implements CubeRepository
{
    private $cubes;

    public function __construct()
    {
        $this->cubes[] = new Cube(
            new CubeId('8CE05088-ED1F-43E9-A415-3B3792655A9B'), 'SIMPLE_BLUE', 0, 'blue_cube_name'
        );
        $this->cubes[] = new Cube(
            new CubeId('62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23'), 'SIMPLE_GREEN', 0, 'green_cube_name'
        );
    }

    public function find(CubeId $cubeId)
    {
    }

    public function findAll()
    {
        return $this->cubes;
    }

    public function add(Cube $cube)
    {
    }

    public function remove(Cube $cube)
    {
    }
}