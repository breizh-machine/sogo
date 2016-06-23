<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\User\User as ScubPlayer;
use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Turn\Turn;
use Scubs\CoreDomain\Turn\TurnId;

class GameTest extends \PHPUnit_Framework_TestCase
{

    public function testVisitorDeclined()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $initialCredits = $local->getCredits();
        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        $this->assertTrue($local->getCredits() == $initialCredits - 20);
        $game->visitorDeclined();
        $this->assertTrue($local->getCredits() == $initialCredits);
        $this->assertTrue($game->isVisitorDeclined());
        try {
            $game->isVisitorDeclined();
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$VISITOR_ALREADY_DECLINED);
        }
    }

    public function testVisitorJoined()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $initialVisitorCredits = $visitor->getCredits();

        $visitorCube = new Cube(new CubeId('visitorCube'), 'test', 'test', 1, 'test');
        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        try {
            $game->visitorJoined($cube);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$SAME_CUBES_IN_GAME);
        }
        $game->visitorJoined($visitorCube);
        $this->assertTrue($game->getVisitorCube()->equals($visitorCube));
        $this->assertTrue($initialVisitorCredits == ($game->getVisitor()->getCredits() + 20));
    }
    
    public function testInviteVisitor()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);

        try {
            $game->inviteVisitor($local);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$SAME_VISITOR_AND_LOCAL);
        }
        $game->inviteVisitor($visitor);
        $this->assertTrue($game->getVisitor()->equals($visitor));
    }

    public function testPlay()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);

        try {
            $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_NOT_STARTED);
        }

        $game->inviteVisitor($visitor);
        try {
            $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_NOT_STARTED);
        }

        $game->visitorJoined($visitorCube);
        try {
            $game->play(new Turn(new TurnId(), $game, $visitor, 0, 0, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$NOT_PLAYER_TURN);
        }
        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 1, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 1, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 2, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 3, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 0, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 3, 0));

        try {
            $game->play(new Turn(new TurnId(), $game, $visitor, 0, 0, 3));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_ENDED);
        }
        
        $this->assertTrue($game->getWinner()->equals($local));
    }

    public function testIsGameWon()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 50, $cube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);

        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 1, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 1, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 1, 0));
        $this->assertFalse($game->isGameWon());

        $game->play(new Turn(new TurnId(), $game, $local, 2, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 3, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 0, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 3, 0));
        $this->assertTrue($game->isGameWon());
        $this->assertTrue($local->getCredits() === 150);
    }

    public function testGetWinningTurns()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);

        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 1, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 1, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 1, 0));
        $this->assertTrue($game->getWinningTurns() === null);

        $game->play(new Turn(new TurnId(), $game, $local, 2, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 3, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 0, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 3, 0));
        $this->assertFalse($game->getWinningTurns() === null);
    }

    public function testCheckIsPlayablePosition()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);

        try {
            $game->play(new Turn(new TurnId(), $game, $local, 0, 1, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$UNPLAYABLE_POSITION);
        }

        try {
            $game->play(new Turn(new TurnId(), $game, $local, 0, -1, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$UNPLAYABLE_POSITION);
        }

        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        try {
            $game->play(new Turn(new TurnId(), $game, $visitor, 0, 0, 0));
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$ALREADY_PLAYED_POSITION);
        }
    }

    public function testIsGameStarted()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);

        $this->assertFalse($game->isGameStarted());
        $game->inviteVisitor($visitor);
        $this->assertFalse($game->isGameStarted());
        $game->visitorJoined($visitorCube);
        $this->assertTrue($game->isGameStarted());
    }

    public function testIsGameEnded()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);
        $this->assertFalse($game->isGameEnded());
        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 1, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 1, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 0, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 2, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 2, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 3, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 2, 0));
        $game->play(new Turn(new TurnId(), $game, $visitor, 0, 1, 0));
        $game->play(new Turn(new TurnId(), $game, $local, 3, 3, 0));
        $this->assertTrue($game->isGameEnded());
    }

    public function testIsPlayerTurn()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $visitorCube = new Cube(new CubeId(), 'test', 'test', 1, 'test');

        $cube = new Cube(new CubeId(), 'test', 'test', 1, 'test');
        $game = new Game(new GameId(), $local, 20, $cube);
        $game->inviteVisitor($visitor);
        $game->visitorJoined($visitorCube);
        $this->assertTrue($game->isScubPlayerTurn($local));
        $game->play(new Turn(new TurnId(), $game, $local, 0, 0, 0));
        $this->assertFalse($game->isScubPlayerTurn($local));
        $this->assertTrue($game->isScubPlayerTurn($visitor));
    }


}