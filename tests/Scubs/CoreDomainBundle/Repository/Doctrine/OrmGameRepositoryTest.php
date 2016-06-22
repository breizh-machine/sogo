<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomainBundle\Entity\Game;
use Scubs\CoreDomainBundle\Entity\User as ScubPlayer;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomain\Turn\TurnId;

class OrmGameRepositoryTest extends BaseOrmRepository
{
    public function setUp()
    {
        $this->init();
    }

    public function testFindAllEndedByPlayerCouple()
    {
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();
        $visitorCube = $this->setVisitorCube();
        $thirdPlayer = new ScubPlayer(new ResourceId('third'));
        $thirdPlayer->setPlainPassword('third');
        $thirdPlayer->setEmail('third@gmail.com');
        $thirdPlayer->setUsername('third');
        $thirdPlayer->setEnabled(true);
        $this->userManager->updateUser($thirdPlayer);

        $game = $this->getGame('agame', $local, $visitor, $localCube, $visitorCube, 25);
        $game2 = $this->getGame('agame2', $thirdPlayer, $visitor, $localCube, $visitorCube, 25);
        $game3 = $this->getGame('agame3', $thirdPlayer, $visitor, $localCube, $visitorCube, 25);
        $game4 = $this->getGame('agame4', $thirdPlayer, $visitor, $localCube, $visitorCube, 25, false);

        $this->assertTrue(count($this->gameRepository->findAllEndedByPlayerCouple((string) $local->getId(), (string) $visitor->getId())) == 1);
        $this->assertTrue(count($this->gameRepository->findAllEndedByPlayerCouple((string) $visitor->getId(), (string) $local->getId())) == 1);
        $this->assertTrue(count($this->gameRepository->findAllEndedByPlayerCouple((string) $local->getId(), (string) $thirdPlayer->getId())) == 0);
        $this->assertTrue(count($this->gameRepository->findAllEndedByPlayerCouple((string) $thirdPlayer->getId(), (string) $visitor->getId())) == 2);
        $this->assertTrue(count($this->gameRepository->findAllEndedByPlayerCouple((string) $visitor->getId(), (string) $thirdPlayer->getId())) == 2);

        $this->gameRepository->remove($game);
        $this->gameRepository->remove($game2);
        $this->gameRepository->remove($game3);
        $this->gameRepository->remove($game4);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
        $this->userManager->deleteUser($thirdPlayer);
    }


    public function testFindAllNotEndedByPlayerCouple()
    {
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();
        $visitorCube = $this->setVisitorCube();
        $thirdPlayer = new ScubPlayer(new ResourceId('third'));
        $thirdPlayer->setPlainPassword('third');
        $thirdPlayer->setEmail('third@gmail.com');
        $thirdPlayer->setUsername('third');
        $thirdPlayer->setEnabled(true);
        $this->userManager->updateUser($thirdPlayer);

        $game = $this->getGame('agame', $local, $visitor, $localCube, $visitorCube, 25);
        $game2 = $this->getGame('agame2', $thirdPlayer, $visitor, $localCube, $visitorCube, 25);
        $game3 = $this->getGame('agame3', $thirdPlayer, $visitor, $localCube, $visitorCube, 25);
        $game4 = $this->getGame('agame4', $thirdPlayer, $visitor, $localCube, $visitorCube, 25, false);

        $this->assertTrue(count($this->gameRepository->findAllNotEndedByPlayerCouple((string)$local->getId(), (string)$visitor->getId())) == 0);
        $this->assertTrue(count($this->gameRepository->findAllNotEndedByPlayerCouple((string)$visitor->getId(), (string)$local->getId())) == 0);
        $this->assertTrue(count($this->gameRepository->findAllNotEndedByPlayerCouple((string)$local->getId(), (string)$thirdPlayer->getId())) == 0);
        $this->assertTrue(count($this->gameRepository->findAllNotEndedByPlayerCouple((string)$thirdPlayer->getId(), (string)$visitor->getId())) == 1);
        $this->assertTrue(count($this->gameRepository->findAllNotEndedByPlayerCouple((string)$visitor->getId(), (string)$thirdPlayer->getId())) == 1);

        $this->gameRepository->remove($game);
        $this->gameRepository->remove($game2);
        $this->gameRepository->remove($game3);
        $this->gameRepository->remove($game4);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
        $this->userManager->deleteUser($thirdPlayer);
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
            $game->visitorJoined($visitorCube);
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
            $game->visitorJoined($visitorCube);
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
            $game2->visitorJoined($visitorCube);
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