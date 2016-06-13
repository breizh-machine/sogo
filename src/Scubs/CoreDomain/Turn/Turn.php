<?php

namespace Scubs\CoreDomain\Turn;

use Scubs\CoreDomain\Core\Resource;

class Turn extends Resource
{
    private $player;
    private $startDate;
    private $x;
    private $y;
    private $z;

    public function __construct(TurnId $turnId, $player, $x, $y, $z)
    {
        parent::__construct($turnId);
        $this->player = $player;
        $this->startDate = new \DateTime();
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    /**
     * @return mixed
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }
    
}