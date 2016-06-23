<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Turn\Turn;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomain\User\User as ScubPlayer;
use Scubs\CoreDomain\Game\GameUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Cube\Cube;

class GameUtilsTest extends \PHPUnit_Framework_TestCase
{
    private $gridSize = 4;

    public function testIsWinningDirection()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $turns = new ArrayCollection();
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 0, 0, 0);
        $t2 =new Turn(new TurnId(), $game, $local, 0, 1, 0);
        $t3 =new Turn(new TurnId(), $game, $visitor, 1, 2, 1);
        $t4 =new Turn(new TurnId(), $game, $local, 0, 2, 0);
        $t5 =new Turn(new TurnId(), $game, $local, 0, 3, 0);
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);
        $turns->add($t4);
        $turns->add($t5);

        $line1 = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $line2 = GameUtils::getPlayablePositions($this->gridSize, $t1, $t3);

        $this->assertTrue(GameUtils::isWinningDirection($turns, $this->gridSize, $t1, $line1));
        $this->assertFalse(GameUtils::isWinningDirection($turns, $this->gridSize, $t1, $line2));

    }

    public function testGetWinningTurnsFromLine()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $turns = new ArrayCollection();
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 0, 0, 0);
        $t2 =new Turn(new TurnId(), $game, $local, 0, 1, 0);
        $t3 =new Turn(new TurnId(), $game, $visitor, 1, 2, 1);
        $t4 =new Turn(new TurnId(), $game, $local, 0, 2, 0);
        $t5 =new Turn(new TurnId(), $game, $local, 0, 3, 0);
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);
        $turns->add($t4);
        $turns->add($t5);

        $line = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $winningTurnsFromLine = GameUtils::getWinningTurnsFromLine($turns, $this->gridSize, $t1, $line);

        $this->assertTrue($winningTurnsFromLine->contains($t1));
        $this->assertTrue($winningTurnsFromLine->contains($t2));
        $this->assertFalse($winningTurnsFromLine->contains($t3));
        $this->assertTrue($winningTurnsFromLine->contains($t4));
        $this->assertTrue($winningTurnsFromLine->contains($t5));
    }

    public function testGetLastTurn()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 1, 1, 2);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 2, 1);
        $t3 =new Turn(new TurnId(), $game, $local, 1, 2, 2);
        $turns = new ArrayCollection();
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);

        $lastTurn = GameUtils::getLastTurn($turns);
        $this->assertTrue($lastTurn->equals($t3));
    }


    public function testGetPlayablePositions()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 1, 1, 2);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 2, 1);
        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $this->assertTrue($playablePositions->contains([1, 1, 2]));
        $this->assertTrue($playablePositions->contains([1, 2, 1]));
        $this->assertTrue($playablePositions->contains([1, 3, 0]));
        $this->assertTrue($playablePositions->contains([1, 0, 3]));
        $this->assertTrue($playablePositions->count() == 4);

        $t1 =new Turn(new TurnId(), $game, $local, 2, 1, 0);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 0, 1);
        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $this->assertTrue($playablePositions->contains([2, 1, 0]));
        $this->assertTrue($playablePositions->contains([1, 0, 1]));
        $this->assertTrue($playablePositions->count() == 2);

        $t1 =new Turn(new TurnId(), $game, $local, 2, 0, 0);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 1, 1);
        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $this->assertTrue($playablePositions->contains([2, 0, 0]));
        $this->assertTrue($playablePositions->contains([1, 1, 1]));
        $this->assertTrue($playablePositions->contains([0, 2, 2]));
        $this->assertTrue($playablePositions->count() == 3);

        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t2, $t1);
        $this->assertTrue($playablePositions->contains([2, 0, 0]));
        $this->assertTrue($playablePositions->contains([1, 1, 1]));
        $this->assertTrue($playablePositions->contains([0, 2, 2]));
        $this->assertTrue($playablePositions->count() == 3);
    }

    public function testGetTurnNeighborhood()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $turns = new ArrayCollection();
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 1, 1, 1);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 0, 1);
        $t3 =new Turn(new TurnId(), $game, $visitor, 1, 2, 1);
        $t4 =new Turn(new TurnId(), $game, $local, 0, 1, 1);
        $t5 =new Turn(new TurnId(), $game, $local, 3, 1, 1);
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);
        $turns->add($t4);
        $turns->add($t5);

        $neighborhood = GameUtils::getTurnNeighborhood($turns, $this->gridSize, $t1);

        $this->assertFalse($neighborhood->contains($t1));
        $this->assertTrue($neighborhood->contains($t2));
        $this->assertFalse($neighborhood->contains($t3));
        $this->assertTrue($neighborhood->contains($t4));
        $this->assertFalse($neighborhood->contains($t5));
    }

    public function testIsOutOfBoundPosition()
    {
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, -1, 0, 0));
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, 4, 0, 0));
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, 0, -1, 0));
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, 0, 4, 0));
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, 0, 0, -1));
        $this->assertTrue(GameUtils::isOutOfBoundPosition(4, 0, 0, 4));
        $this->assertFalse(GameUtils::isOutOfBoundPosition(4, 0, 0, 0));
        $this->assertFalse(GameUtils::isOutOfBoundPosition(4, 3, 3, 3));
    }

    public function testGetTurnAtPosition()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $turns = new ArrayCollection();
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 1, 0, 1);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 1, 1);
        $t3 =new Turn(new TurnId(), $game, $local, 1, 2, 1);
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);

        $turnAtPosition = GameUtils::getTurnAtPosition($turns, $this->gridSize, 1, 1, 1);
        $this->assertTrue($turnAtPosition->equals($t2));

        $turnAtPosition = GameUtils::getTurnAtPosition($turns, $this->gridSize, 0, 0, 0);
        $this->assertTrue($turnAtPosition === null);

        $turnAtPosition = GameUtils::getTurnAtPosition($turns, $this->gridSize, -1, 0, 0);
        $this->assertTrue($turnAtPosition === null);
    }

    public function testGetTurnsAtPosition()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $turns = new ArrayCollection();
        $game = new Game(new GameId(), $local, 25, new Cube(new CubeId(), 'test', 'test', 0, 'test'));
        $t1 =new Turn(new TurnId(), $game, $local, 1, 0, 1);
        $t2 =new Turn(new TurnId(), $game, $local, 1, 1, 1);
        $t3 =new Turn(new TurnId(), $game, $local, 1, 2, 1);
        $t4 =new Turn(new TurnId(), $game, $local, 0, 3, 1);
        $turns->add($t1);
        $turns->add($t2);
        $turns->add($t3);
        $turns->add($t4);

        $turnsAtPosition = GameUtils::getTurnsAtPosition($turns, $this->gridSize, 1, 1);
        $this->assertTrue($turnsAtPosition->contains($t1));
        $this->assertTrue($turnsAtPosition->contains($t2));
        $this->assertTrue($turnsAtPosition->contains($t3));
        $this->assertFalse($turnsAtPosition->contains($t4));
    }

}