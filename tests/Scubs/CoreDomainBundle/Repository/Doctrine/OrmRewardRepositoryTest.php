<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomainBundle\Entity\Reward;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomainBundle\Entity\Game;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomain\Turn\TurnId;

class OrmRewardRepositoryTest extends BaseOrmRepository
{
    public function setUp()
    {
        $this->init();
    }

    public function testCrud()
    {
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $reward = new Reward(new RewardId('initial'), $cube);
        $reward2 = new Reward(new RewardId(), $cube);
        $this->rewardRepository->add($reward);
        $this->rewardRepository->add($reward2);
        $this->assertTrue(count($this->rewardRepository->findAll()) == 2);
        $this->rewardRepository->remove($reward);
        $this->assertTrue(count($this->rewardRepository->findAll()) == 1);
        $this->rewardRepository->remove($reward2);
        $this->cubeRepository->remove($cube);
    }

    public function testFindRewardByCubeAndUser()
    {
        $localCube = new Cube(new CubeId('local'), 'test', 'thumbnail', 0, 'description');
        $visitorCube = new Cube(new CubeId('visitor'), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($localCube);
        $this->cubeRepository->add($visitorCube);
        $reward = new Reward(new RewardId('initial'), $localCube);

        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $game = $this->gameRepository->find(new GameId('agame'));
        if (!$game) {
            $game = new Game(new GameId('agame'), $local, 25, $localCube);
            $game->inviteVisitor($visitor);
            $game->visitorJoined($visitorCube);
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
            $this->gameRepository->add($game);
            $reward->assignToGame($game);
            $this->rewardRepository->add($reward);
        }

        $this->assertTrue(count($this->rewardRepository->findRewardByCubeAndUser( (string) $visitor->getId(), (string) $localCube->getId())) == 0);
        $this->assertTrue(count($this->rewardRepository->findRewardByCubeAndUser( (string) $local->getId(), (string) $localCube->getId())) == 1);
        $this->assertTrue(count($this->rewardRepository->findRewardByCubeAndUser( (string) $local->getId(), (string) $visitorCube->getId())) == 0);

        $this->rewardRepository->remove($reward);
        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testFindRewardBydUser()
    {
        $localCube = new Cube(new CubeId('local'), 'test', 'thumbnail', 0, 'description');
        $visitorCube = new Cube(new CubeId('visitor'), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($localCube);
        $this->cubeRepository->add($visitorCube);
        $reward = new Reward(new RewardId('initial'), $localCube);

        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $game = $this->gameRepository->find(new GameId('agame'));
        if (!$game) {
            $game = new Game(new GameId('agame'), $local, 25, $localCube);
            $game->inviteVisitor($visitor);
            $game->visitorJoined($visitorCube);
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
            $this->gameRepository->add($game);
            $reward->assignToGame($game);
            $this->rewardRepository->add($reward);
        }

        $this->assertTrue(count($this->rewardRepository->findRewardsByUser( (string) $visitor->getId())) == 0);
        $this->assertTrue(count($this->rewardRepository->findRewardsByUser( (string) $local->getId())) == 1);

        $this->rewardRepository->remove($reward);
        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }


    public function testFindRewardBydGame()
    {
        $localCube = new Cube(new CubeId('local'), 'test', 'thumbnail', 0, 'description');
        $visitorCube = new Cube(new CubeId('visitor'), 'test', 'thumbnail', 0, 'description');
        $this->cubeRepository->add($localCube);
        $this->cubeRepository->add($visitorCube);
        $reward = new Reward(new RewardId('initial'), $localCube);

        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $game = $this->gameRepository->find(new GameId('agame'));
        if (!$game) {
            $game = new Game(new GameId('agame'), $local, 25, $localCube);
            $game->inviteVisitor($visitor);
            $game->visitorJoined($visitorCube);
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
            $this->gameRepository->add($game);
            $reward->assignToGame($game);
            $this->rewardRepository->add($reward);
        }

        $this->assertTrue($this->rewardRepository->findRewardByGame( (string) $game->getId()) !== null) ;
        $this->assertTrue($this->rewardRepository->findRewardByGame('test') == null);

        $this->rewardRepository->remove($reward);
        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

}