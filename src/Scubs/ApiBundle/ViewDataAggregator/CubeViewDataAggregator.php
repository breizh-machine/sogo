<?php

namespace Scubs\ApiBundle\ViewDataAggregator;

use Scubs\CoreDomain\Cube\Cube;

class CubeViewDataAggregator implements ViewDataAggregator
{
    protected $cube;

    public function __construct(Cube $cube)
    {
        $this->cube = $cube;
    }

    /**
     * @return Cube
     */
    public function getCube()
    {
        return $this->cube;
    }
}