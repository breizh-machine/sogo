<?php

namespace Scubs\CoreDomain\Cube;

use Scubs\CoreDomain\Core\ResourceRepository;

interface CubeRepository extends ResourceRepository
{
    public function getCubesByRarity($rarity);
}