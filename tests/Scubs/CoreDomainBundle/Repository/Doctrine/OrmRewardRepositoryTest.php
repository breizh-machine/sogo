<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomainBundle\Entity\Reward;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomainBundle\Entity\Game;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomainBundle\Entity\User as ScubPlayer;
use Scubs\CoreDomain\Turn\TurnId;

class OrmRewardRepositoryTest extends BaseOrmRepository
{
    private $cubeRepository;
    private $rewardRepository;
    private $turnRepository;
    private $gameRepository;
    private $userManager;

    public function setUp()
    {
        $this->init('cube_repository.doctrine_orm');
        $this->init('reward_repository.doctrine_orm');
        $this->init('turn_repository.doctrine_orm');
        $this->init('game_repository.doctrine_orm');
        $this->init('fos_user.user_manager');
        $this->cubeRepository = $this->repositories['cube_repository.doctrine_orm'];
        $this->rewardRepository = $this->repositories['reward_repository.doctrine_orm'];
        $this->turnRepository = $this->repositories['turn_repository.doctrine_orm'];
        $this->gameRepository = $this->repositories['game_repository.doctrine_orm'];
        $this->userManager = $this->repositories['fos_user.user_manager'];
    }

    private function setLocal()
    {
        $local = $this->userManager->findUserBy(['id' => 'LOCAL']);
        if (!$local) {
            $local = new ScubPlayer(new ResourceId('LOCAL'));
            $local->setPlainPassword('local');
            $local->setEmail('email@gmail.com');
            $local->setUsername('local');
            $local->setEnabled(true);
            $this->userManager->updateUser($local);
        }
        return $local;
    }

    private function setVisitor()
    {
        $visitor = $this->userManager->findUserBy(['id' => 'VISITOR']);
        if (!$visitor) {
            $visitor = new ScubPlayer(new ResourceId('VISITOR'));
            $visitor->setPlainPassword('visitor');
            $visitor->setEmail('visitor@gmail.com');
            $visitor->setUsername('visitor');
            $visitor->setEnabled(true);
            $this->userManager->updateUser($visitor);
        }
        return $visitor;
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
            $game->assignVisitorCube($visitorCube);
            $game->play(new Turn(new TurnId(), $local, 0, 0, 0));
            $game->play(new Turn(new TurnId(), $visitor, 1, 0, 0));
            $game->play(new Turn(new TurnId(), $local, 1, 1, 0));
            $game->play(new Turn(new TurnId(), $visitor, 2, 0, 0));
            $game->play(new Turn(new TurnId(), $local, 3, 0, 0));
            $game->play(new Turn(new TurnId(), $visitor, 2, 1, 0));
            $game->play(new Turn(new TurnId(), $local, 2, 2, 0));
            $game->play(new Turn(new TurnId(), $visitor, 3, 1, 0));
            $game->play(new Turn(new TurnId(), $local, 3, 2, 0));
            $game->play(new Turn(new TurnId(), $visitor, 0, 1, 0));
            $game->play(new Turn(new TurnId(), $local, 3, 3, 0));
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
}