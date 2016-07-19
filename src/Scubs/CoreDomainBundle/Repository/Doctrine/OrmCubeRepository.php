<?php

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Cube\CubeRepository;

class OrmCubeRepository extends OrmResourceRepository implements CubeRepository
{
    public function getCubesByRarity($rarity)
    {
        $query = $this->getManager()->createQuery('
        SELECT c
        FROM ScubsCoreDomainBundle:Cube c
        WHERE c.rarity = :rarity'
        )->setParameter('rarity', $rarity);

        return $query->getResult();
    }

    public function getAllNativeCubes()
    {
        $query = $this->getManager()->createQuery('
        SELECT c
        FROM ScubsCoreDomainBundle:Cube c
        WHERE c.native = TRUE'
        );

        return $query->getResult();
    }

    public function getAvailableNativeCubes($cubeId)
    {
        $query = $this->getManager()->createQuery('
        SELECT c
        FROM ScubsCoreDomainBundle:Cube c
        WHERE c.native = TRUE AND c.id != :cube_id'
        )->setParameter('cube_id', $cubeId);

        return $query->getResult();
    }

}