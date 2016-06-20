<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomainBundle\Entity\Game;
use Scubs\CoreDomainBundle\Entity\User as ScubPlayer;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomain\Turn\TurnId;

class OrmGameRepositoryTest extends BaseOrmRepository
{
    private $cubeRepository;
    private $turnRepository;
    private $gameRepository;
    private $userManager;

    public function setUp()
    {
        $this->init('cube_repository.doctrine_orm');
        $this->init('turn_repository.doctrine_orm');
        $this->init('game_repository.doctrine_orm');
        $this->init('fos_user.user_manager');
        $this->cubeRepository = $this->repositories['cube_repository.doctrine_orm'];
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

    private function setLocalCube()
    {
        $localCube = $this->cubeRepository->find(new CubeId('cube1'));
        if (!$localCube) {
            $localCube = new Cube(new CubeId('cube1'), 'test', 'test', 1, 'test');
            $this->cubeRepository->add($localCube);
        }
        return $localCube;
    }

    private function setVisitorCube()
    {
        $visitorCube = $this->cubeRepository->find(new CubeId('cube2'));
        if (!$visitorCube) {
            $visitorCube = new Cube(new CubeId('cube2'), 'test', 'test', 1, 'test');
            $this->cubeRepository->add($visitorCube);
        }
        return $visitorCube;
    }

    public function testCrud()
    {
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();
        $visitorCube = $this->setVisitorCube();

        $game = $this->gameRepository->find(new GameId('agame'));
        if (!$game) {
            $game = new Game(new GameId('agame'), $local, 25, $localCube);
            $game->inviteVisitor($visitor);
            $game->assignVisitorCube($visitorCube);
            $game->play(new Turn(new TurnId(), $local, 0, 0, 0));
            $game->play(new Turn(new TurnId(), $visitor, 1, 0, 0));
            $game->play(new Turn(new TurnId(), $local, 1, 1, 0));
            $this->gameRepository->add($game);
        }

        $this->assertTrue($game->getVisitorCube()->equals($visitorCube));

        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testFindAllByUserIdOrderedByDate()
    {
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();
        $visitorCube = $this->setVisitorCube();

        $game = $this->gameRepository->find(new GameId('agame'));
        if (!$game) {
            $game = new Game(new GameId('agame'), $local, 25, $localCube);
            $game->inviteVisitor($visitor);
            $game->assignVisitorCube($visitorCube);
            $this->gameRepository->add($game);
            $game->play(new Turn(new TurnId(), $local, 0, 0, 0));
            $game->play(new Turn(new TurnId(), $visitor, 1, 0, 0));
            $game->play(new Turn(new TurnId(), $local, 1, 1, 0));
            $this->gameRepository->update($game);
        }

        $thirdPlayer = new ScubPlayer(new ResourceId('third'));
        $thirdPlayer->setPlainPassword('third');
        $thirdPlayer->setEmail('third@gmail.com');
        $thirdPlayer->setUsername('third');
        $thirdPlayer->setEnabled(true);
        $this->userManager->updateUser($thirdPlayer);

        $game2 = $this->gameRepository->find(new GameId('agame2'));
        if (!$game2) {
            $game2 = new Game(new GameId('agame2'), $thirdPlayer, 25, $localCube);
            $game2->inviteVisitor($visitor);
            $game2->assignVisitorCube($visitorCube);
            $game2->play(new Turn(new TurnId(), $local, 0, 0, 0));
            $game2->play(new Turn(new TurnId(), $visitor, 1, 0, 0));
            $game2->play(new Turn(new TurnId(), $local, 1, 1, 0));
            $this->gameRepository->add($game2);
        }

        $gamesForLocal = $this->gameRepository->findAllByUserIdOrderedByDate((string) $local->getId());
        $this->assertTrue(count($gamesForLocal) == 1);
        $gamesForVisitor = $this->gameRepository->findAllByUserIdOrderedByDate((string) $visitor->getId());
        $this->assertTrue(count($gamesForVisitor) == 2);

        $this->gameRepository->remove($game);
        $this->gameRepository->remove($game2);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
        $this->userManager->deleteUser($thirdPlayer);
    }

}