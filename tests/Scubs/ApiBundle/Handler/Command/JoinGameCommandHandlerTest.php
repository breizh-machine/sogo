<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Game\GameLogicException;
use Tests\Scubs\CoreDomainBundle\Repository\Doctrine\BaseOrmRepository;
use Scubs\ApiBundle\Command\JoinGameCommand;

class JoinGameCommandHandlerTest extends BaseOrmRepository
{
    protected $handler;

    public function setUp()
    {
        $this->init();
        $this->handler = $this->container->get('scubs.api.handler.command.game.join');
    }

    public function testJoinCommandHandlerOk()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

        $this->handler->handle($command);

        $this->assertTrue($game->isGameStarted());

        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testJoinAfterEndedGame()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);
        $gameWon = $this->getGame('won', $local, $visitor, $localCube, $visitorCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

        $this->handler->handle($command);

        $this->assertTrue($game->isGameStarted());

        $this->gameRepository->remove($gameWon);
        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testNonExistingVisitor()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = 'NOT EXISTING';
        $command->visitorCubeId = (string) $visitorCube->getId();

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


    public function testVisitorHasCredits()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $local->wonBet(2500);
        $this->userManager->updateUser($local);
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 2500);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }


    public function testGameDoesNotExist()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = 'not existing';
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

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

    public function testSameVisitorAndLocal()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $game->getLocal()->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$SAME_VISITOR_AND_LOCAL);
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
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $visitorCube->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$VISITOR_ALREADY_SET);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }


    public function testNonExistingVisitorCube()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = 'non existing';

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$NO_VISITOR_CUBE_FOUND);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }


    public function testNonOwnedVisitorCube()
    {
        $command = new JoinGameCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $nonOwnedCube = $this->setCube('non owned', 'test', 'test', 0, 'test', false);
        $game = $this->getNotJoinedGame('id', $local, $visitor, $localCube, 25);

        $command->gameId = (string) $game->getId();
        $command->visitorId = (string) $visitor->getId();
        $command->visitorCubeId = (string) $nonOwnedCube->getId();

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$LOCAL_CUBE_NOT_OWNED);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->cubeRepository->remove($nonOwnedCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }
}