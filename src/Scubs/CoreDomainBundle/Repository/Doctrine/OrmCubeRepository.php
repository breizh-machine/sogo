<?php
/**
 * Created by PhpStorm.
 * User: FMARTIN
 * Date: 15/06/2016
 * Time: 11:39
 */

namespace Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Cube\CubeRepository;

class OrmCubeRepository extends OrmResourceRepository implements CubeRepository
{
    public function getCubesByRarity($rarity)
    {
        return null;
    }
}