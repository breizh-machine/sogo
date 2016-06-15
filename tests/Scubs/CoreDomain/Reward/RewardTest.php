<?php

namespace Test\Scubs\CoreDomain\Reward;

use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Reward\Reward;
use Scubs\CoreDomain\User\User as ScubPlayer;
use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomain\Cube\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Core\ResourceId;

class RewardTest extends \PHPUnit_Framework_TestCase
{
    public function testAssignToGame()
    {
        $local = new ScubPlayer(new ResourceId('LOCAL'));
        $game = new Game(new GameId(), $local, 20, new CubeId());
        $cube = $cube = new Cube(new CubeId(), 'text', 'text', 0, 'name');
        $reward = new Reward(new RewardId(), $cube);

        $reward->assignToGame($game->getId());
        try {
            $reward->assignToGame($game->getId());
        } catch (\Exception $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$REWARD_ALREADY_ASSIGNED_TO_GAME);
        }
    }
}