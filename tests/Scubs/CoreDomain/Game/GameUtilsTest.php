<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Turn\Turn;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomain\Player\Player;
use Scubs\CoreDomain\Player\PlayerId;
use Scubs\CoreDomain\Game\GameUtils;

class GameUtilsTest extends \PHPUnit_Framework_TestCase
{
    private $gridSize = 4;

    public function testGetPlayablePositions()
    {
        $local = new Player(new PlayerId('LOCAL'));
        $t1 = new Turn(new TurnId(), $local, 1, 1, 2);
        $t2 = new Turn(new TurnId(), $local, 1, 2, 1);
        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $this->assertTrue($playablePositions->contains([1, 1, 2]));
        $this->assertTrue($playablePositions->contains([1, 2, 1]));
        $this->assertTrue($playablePositions->contains([1, 3, 0]));
        $this->assertTrue($playablePositions->contains([1, 0, 3]));
        $this->assertTrue($playablePositions->count() == 4);

        $t1 = new Turn(new TurnId(), $local, 2, 1, 0);
        $t2 = new Turn(new TurnId(), $local, 1, 0, 1);
        $playablePositions = GameUtils::getPlayablePositions($this->gridSize, $t1, $t2);
        $this->assertTrue($playablePositions->contains([2, 1, 0]));
        $this->assertTrue($playablePositions->contains([1, 0, 1]));
        $this->assertTrue($playablePositions->count() == 2);

        $t1 = new Turn(new TurnId(), $local, 2, 0, 0);
        $t2 = new Turn(new TurnId(), $local, 1, 1, 1);
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
}