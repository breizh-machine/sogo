<?php

namespace Scubs\CoreDomain\Cube;

interface CubeRepository
{
    public function getCubesByRarity($rarity);
}