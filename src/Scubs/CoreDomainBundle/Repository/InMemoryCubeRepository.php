<?php

namespace Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Cube\CubeRepository;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryCubeRepository extends InMemoryResourceRepository  implements CubeRepository
{
    public function __construct()
    {
        parent::__construct('Scubs\CoreDomain\Cube\Cube', 'Scubs\CoreDomain\Cube\CubeId');
        $this->resources->add(new Cube(
            new CubeId(), 'green-grenat.jpg', 'green-grenat-preview.jpg', 0, 'green_grenat_cube_name'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'blue-stone.jpg', 'blue-stone-preview.jpg', 0, 'blue_stone_cube_name'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 0, 'yellow_cube_name'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 1, 'uncommon_cube_name'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 1, 'uncommon_cube_name2'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 2, 'rare_cube_name1'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 3, 'very_rare_cube_name2'
        ));
        $this->resources->add(new Cube(
            new CubeId(), 'SIMPLE_GREEN', 'SIMPLE_GREEN', 4, 'uniq_cube_name'
        ));
    }

    public function getCubesByRarity($rarity)
    {
        $cubes = new ArrayCollection();
        foreach ($this->resources as $cube) {
            if ($cube->getRarity() == $rarity) {
                $cubes->add($cube);
            }
        }
        return $cubes;
    }

}