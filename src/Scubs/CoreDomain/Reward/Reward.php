<?php

namespace Scubs\CoreDomain\Reward;

use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Core\Resource;

class Reward extends Resource
{
    private $cube;
    private $startDate;

    public function __construct(RewardId $id, Cube $cube)
    {
        parent::__construct($id);
        $this->cube = $cube;
        $this->startDate = new \DateTime();
    }

    /**
     * @return Cube
     */
    public function getCube()
    {
        return $this->cube;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
}