<?php

namespace Scubs\CoreDomain\Game;

use Doctrine\Common\Collections\ArrayCollection;
use Scubs\CoreDomain\Core\Resource;
use Scubs\CoreDomain\Turn\Turn;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomain\Player\Player;

class Game extends Resource
{
    private static $GRID_SIZE = 4;

    private $turns;
    private $startDate;
    private $endDate;
    private $local;
    private $visitor;
    private $winner;

    public function __construct(GameId $gameId, Player $local)
    {
        parent::__construct($gameId);
        $this->startDate = new \DateTime();
        $this->endDate = null;
        $this->local = $local;
        $this->visitor = null;
        $this->turns = new ArrayCollection();
        $this->winner = null;
    }

    /**
     * @param $visitor
     * @return $this
     * @throws GameLogicException
     */
    public function inviteVisitor($visitor)
    {
        if ($this->local->equals($visitor)) {
            throw new GameLogicException(GameLogicException::$SAME_VISITOR_AND_LOCAL_MESS, GameLogicException::$SAME_VISITOR_AND_LOCAL);
        }
        $this->visitor = $visitor;
        return $this;
    }

    /**
     * @param $player
     * @param $x
     * @param $y
     * @param $z
     * @return $this
     * @throws GameLogicException
     */
    public function play(Player $player, $x, $y, $z)
    {
        //Check the game is started
        if (!$this->isGameStarted()) {
            throw new GameLogicException(GameLogicException::$GAME_NOT_STARTED_MESS, GameLogicException::$GAME_NOT_STARTED);
        }
        //Check that the game is not ended
        if ($this->isGameEnded()) {
            throw new GameLogicException(GameLogicException::$GAME_ENDED_MESS, GameLogicException::$GAME_ENDED);
        }
        //Check that the player is allowed to play
        if (!$this->isPlayerTurn($player)) {
            throw new GameLogicException(GameLogicException::$NOT_PLAYER_TURN_MESS, GameLogicException::$NOT_PLAYER_TURN);
        }
        
        //Check that the position can be played
        $this->checkIsPlayablePosition($x, $y, $z);

        //Create the turn
        $this->turns->add(new Turn(new TurnId(), $player, $x, $y, $z));

        //If game is won, update winner and endDate
        $winningTurns = $this->getWinningTurns();
        if ($winningTurns !== null)
        {
            $this->winner = $winningTurns->get(0)->getPlayer();
            $this->endDate = new \DateTime();
        }

        return $this;
    }

    public function isGameWon()
    {
        return $this->getWinningTurns() !== null;
    }

    public function getWinningTurns()
    {
        foreach ($this->turns as $turn)
        {
            $neighborhood = GameUtils::getTurnNeighborhood($this->turns, self::$GRID_SIZE, $turn);
            $lines = new ArrayCollection();
            foreach ($neighborhood as $neighbor)
            {
                $playablePositions = GameUtils::getPlayablePositions(self::$GRID_SIZE, $turn, $neighbor);
                if ($playablePositions !== null) {
                    $lines->add($playablePositions);
                }
            }
            foreach ($lines as $line)
            {
                if (GameUtils::isWinningDirection($this->turns, self::$GRID_SIZE, $turn, $line))
                {
                    return GameUtils::getWinningTurnsFromLine($this->turns, self::$GRID_SIZE, $turn, $line);
                }
            }
        }
        return null;
    }


    public function checkIsPlayablePosition($x, $y, $z)
    {
        if (GameUtils::isOutOfBoundPosition(self::$GRID_SIZE, $x, $y, $z))
        {
            throw new GameLogicException(GameLogicException::$UNPLAYABLE_POSITION_MESS, GameLogicException::$UNPLAYABLE_POSITION);
        }
        $turnsAtXzPosition = GameUtils::getTurnsAtPosition($this->turns, self::$GRID_SIZE, $x, $z);
        if ($turnsAtXzPosition->get($y) !== null)
        {
            throw new GameLogicException(GameLogicException::$ALREADY_PLAYED_POSITION_MESS, GameLogicException::$ALREADY_PLAYED_POSITION);
        } else if ($y > 0 && GameUtils::getTurnAtPosition($this->turns, self::$GRID_SIZE, $x, $y - 1, $z) == null) {
            throw new GameLogicException(GameLogicException::$UNPLAYABLE_POSITION_MESS, GameLogicException::$UNPLAYABLE_POSITION);
        }
        return true;
    }


    public function isGameStarted()
    {
        return $this->visitor !== null;

    }

    public function isGameEnded()
    {
        return $this->endDate !== null;
    }

    public function isPlayerTurn(Player $player)
    {
        $lastTurn = GameUtils::getLastTurn($this->turns);
        if ($lastTurn !== null) {
            return !$player->equals($lastTurn->getPlayer());
        } else {
            return !$player->equals($this->visitor);
        }
    }

    
    /**
     * @return mixed
     */
    public function getTurns()
    {
        return $this->turns;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @return mixed
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @return null
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param null $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }
}