<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\User\User as ScubPlayer;
use Scubs\CoreDomain\Reward\Reward;
use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Core\ResourceId;

class GameTest extends \PHPUnit_Framework_TestCase
{

    public function testAssignReward()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $game->inviteVisitor($visitor);
        $cube = new Cube(new CubeId(), 'text', 0, 'name');
        $reward = new Reward(new RewardId(), $cube);
        
        try {
            $game->assignReward($reward);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_NOT_ENDED_FOR_REWARD);
        }

        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);
        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);
        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);
        $game->play($local, 3, 3, 0);

        $game->assignReward($reward);
        $this->assertTrue($game->getReward()->equals($reward));
        try {
            $game->assignReward($reward);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$REWARD_ALREADY_ASSIGNED);
        }
    }
    
    public function testInviteVisitor()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);

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
        $game = new Game(new GameId(), $local);

        try {
            $game->play($local, 0, 0, 0);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_NOT_STARTED);
        }

        $game->inviteVisitor($visitor);
        try {
            $game->play($visitor, 0, 0, 0);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$NOT_PLAYER_TURN);
        }
        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);
        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);
        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);
        $game->play($local, 3, 3, 0);

        try {
            $game->play($visitor, 0, 0, 3);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$GAME_ENDED);
        }
        
        $this->assertTrue($game->getWinner()->equals($local));
    }

    public function testIsGameWon()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local, 50);
        $game->inviteVisitor($visitor);

        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);
        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $this->assertFalse($game->isGameWon());

        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);
        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);
        $game->play($local, 3, 3, 0);
        $this->assertTrue($game->isGameWon());
        $this->assertTrue($local->getCredits() === 200);
    }

    public function testGetWinningTurns()
    {

        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $game->inviteVisitor($visitor);

        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);
        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $this->assertTrue($game->getWinningTurns() === null);

        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);
        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);
        $game->play($local, 3, 3, 0);
        $this->assertFalse($game->getWinningTurns() === null);
    }

    public function testCheckIsPlayablePosition()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $game->inviteVisitor($visitor);

        try {
            $game->play($local, 0, 1, 0);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$UNPLAYABLE_POSITION);
        }

        try {
            $game->play($local, 0, -1, 0);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$UNPLAYABLE_POSITION);
        }

        $game->play($local, 0, 0, 0);
        try {
            $game->play($visitor, 0, 0, 0);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$ALREADY_PLAYED_POSITION);
        }
    }

    public function testIsGameStarted()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $this->assertFalse($game->isGameStarted());
        $game->inviteVisitor($visitor);
        $this->assertTrue($game->isGameStarted());
    }

    public function testIsGameEnded()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $game->inviteVisitor($visitor);
        $this->assertFalse($game->isGameEnded());
        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);
        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);
        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);
        $game->play($local, 3, 3, 0);
        $this->assertTrue($game->isGameEnded());
    }

    public function testIsPlayerTurn()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $visitor = new ScubPlayer(new ResourceId('VISITOR'));
        $game = new Game(new GameId(), $local);
        $game->inviteVisitor($visitor);
        $this->assertTrue($game->isScubPlayerTurn($local));
        $game->play($local, 0, 0, 0);
        $this->assertFalse($game->isScubPlayerTurn($local));
        $this->assertTrue($game->isScubPlayerTurn($visitor));
    }


}