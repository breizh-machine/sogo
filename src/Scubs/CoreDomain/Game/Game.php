<?php

namespace Scubs\CoreDomain\Game;

use Doctrine\Common\Collections\ArrayCollection;
use Scubs\CoreDomain\Core\Resource;
use Scubs\CoreDomain\Turn\Turn;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomain\Player\ScubPlayer;
use Scubs\CoreDomain\Cube\Cube;

class Game extends Resource
{
    private static $GRID_SIZE = 4;

    protected $turns;
    protected $startDate;
    protected $endDate;
    protected $local;
    protected $visitor;
    protected $winner;
    protected $bet;
    protected $localCube;
    protected $visitorCube;
    protected $visitorJoined;
    protected $visitorDeclined;

    public function __construct(GameId $gameId, ScubPlayer $local, $bet, Cube $localCube)
    {
        parent::__construct($gameId);
        $this->startDate = new \DateTime();
        $this->endDate = null;
        $this->local = $local;
        $this->visitor = null;
        $this->turns = new ArrayCollection();
        $this->winner = null;
        $this->bet = $bet;
        $this->localCube = $localCube;
        $this->visitorJoined = false;
        $this->visitorDeclined = false;
        $this->local->bet($this->bet);
    }

    public function visitorDeclined()
    {
        if ($this->visitorDeclined) {
            throw new GameLogicException(GameLogicException::$VISITOR_ALREADY_DECLINED_MESS, GameLogicException::$VISITOR_ALREADY_DECLINED);
        }
        $this->visitorDeclined = true;
        $this->local->refund($this->bet);
        return $this;
    }

    private function assignVisitorCube(Cube $visitorCube)
    {
        if ($this->localCube->equals($visitorCube)) {
            throw new GameLogicException(GameLogicException::$SAME_CUBES_IN_GAME_MESS, GameLogicException::$SAME_CUBES_IN_GAME);
        }
        $this->visitorCube = $visitorCube;
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

    public function visitorJoined(Cube $visitorCube)
    {
        $this->assignVisitorCube($visitorCube);
        $this->visitorJoined = true;
        $this->visitor->bet($this->bet);
        return $this;
    }

    /**
     * @param Turn $turn
     * @return $this
     * @throws GameLogicException
     */
    public function play(Turn $turn)
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
        if (!$this->isScubPlayerTurn($turn->getPlayer())) {
            throw new GameLogicException(GameLogicException::$NOT_PLAYER_TURN_MESS, GameLogicException::$NOT_PLAYER_TURN);
        }
        
        //Check that the position can be played
        $this->checkIsPlayablePosition($turn->getX(), $turn->getY(), $turn->getZ());

        //Create the turn
        $this->turns->add($turn);

        //If game is won, update winner and endDate
        $winningTurns = $this->getWinningTurns();
        if ($winningTurns !== null)
        {
            $this->winner = $winningTurns->get(0)->getPlayer();
            $this->endDate = new \DateTime();
            if ($this->winner->equals($this->local)) {
                $this->local->wonBet($this->bet);
            } else {
                $this->visitor->wonBet($this->bet);
            }
        }

        return $this;
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

    public function isGameStarted()
    {
        return $this->visitor !== null && $this->visitorCube !== null;

    }

    public function isGameEnded()
    {
        return $this->endDate !== null;
    }

    public function isScubPlayerTurn(ScubPlayer $player)
    {
        $lastTurn = GameUtils::getLastTurn($this->turns);
        if ($lastTurn !== null) {
            return !$player->equals($lastTurn->getPlayer());
        } else {
            return $this->visitor !== null && !$player->equals($this->visitor);
        }
    }

    public function getLastTurn()
    {
        return GameUtils::getLastTurn($this->turns);
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
     * @return mixed
     */
    public function getBet()
    {
        return $this->bet;
    }

    /**
     * @return boolean
     */
    public function isVisitorJoined()
    {
        return $this->visitorJoined;
    }

    /**
     * @return Cube
     */
    public function getLocalCube()
    {
        return $this->localCube;
    }

    /**
     * @return mixed
     */
    public function getVisitorCube()
    {
        return $this->visitorCube;
    }

    /**
     * @return boolean
     */
    public function isVisitorDeclined()
    {
        return $this->visitorDeclined;
    }

}