<?php

namespace Scubs\CoreDomain\Reward;

use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Core\Resource;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Game\Game;

class Reward extends Resource
{
    protected $cube;
    protected $startDate;
    protected $game;

    public function __construct(RewardId $id, Cube $cube)
    {
        parent::__construct($id);
        $this->cube = $cube;
        $this->startDate = new \DateTime();
        $this->game = null;
    }

    public function assignToGame(Game $game)
    {
        if (!$this->game) {
            $this->game = $game;
        } else {
            throw new GameLogicException(GameLogicException::$REWARD_ALREADY_ASSIGNED_TO_GAME_MESS, GameLogicException::$REWARD_ALREADY_ASSIGNED_TO_GAME);
        }
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