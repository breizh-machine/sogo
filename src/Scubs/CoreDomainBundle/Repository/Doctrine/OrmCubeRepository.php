<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Cube\CubeRepository;

class OrmCubeRepository extends OrmResourceRepository implements CubeRepository
{
    public function getCubesByRarity($rarity)
    {
        return null;
    }
}