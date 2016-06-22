<?php

namespace Tests\Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameLogicException;
use Tests\Scubs\CoreDomainBundle\Repository\Doctrine\BaseOrmRepository;
use Scubs\ApiBundle\Command\DenyInvitationCommand;

class DenyInvitationCommandHandlerTest extends BaseOrmRepository
{
    protected $handler;

    public function setUp()
    {
        $this->init();
        $this->handler = $this->container->get('scubs.api.handler.command.game.deny');
    }

    public function testDenyInvitation()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $initialCredits = $local->getCredits();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25, true);
        $this->assertTrue($initialCredits == ($local->getCredits() + 25));

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();

        $this->handler->handle($command);

        $this->assertTrue($game->isVisitorDeclined());
        $this->assertTrue($initialCredits == $local->getCredits());

        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);

    }

    public function testGameNotFound()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25, true);

        $command->gameId = 'NOT EXISTING GAME';
        $command->visitorId = (string) $visitor->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$GAME_NOT_FOUND);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }

    public function testVisitorNotFound()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25, true);

        $command->gameId = (string) $game->getId();
        $command->visitorId ='not existing';

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$NO_VISITOR_FOUND);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }

    public function testVisitorNotInGame()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25, true);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $local->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$VISITOR_NOT_IN_GAME);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }


    public function testVisitorAlreadyDeclined()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25, true);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $this->handler->handle($command);

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$VISITOR_ALREADY_DECLINED);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }

    public function testGameAlreadyStarted()
    {
        $command = new DenyInvitationCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$GAME_ALREADY_STARTED);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }

    /*

    */


}