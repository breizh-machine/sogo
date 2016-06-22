<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Game\GameLogicException;
use Symfony\Component\HttpFoundation\Response;
use Tests\Scubs\CoreDomainBundle\Repository\Doctrine\BaseOrmRepository;
use Scubs\CoreDomain\Game\GameId;
use Scubs\ApiBundle\Command\CreateGameCommand;

class CreateGameCommandHandlerTest extends BaseOrmRepository
{
    protected $handler;

    public function setUp()
    {  
        $this->init();
        $this->handler = $this->container->get('scubs.api.handler.command.game');
    }
    
    public function testCreateCommandHandlerOk()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = 5;

        $this->handler->handle($command);

        $url = $command->response->headers->get('Location');
        $matches = array();
        preg_match('/([a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})/', $url, $matches);
        $this->assertTrue(count($matches) > 0);
        $gameId = $matches[1];
        $game = $this->gameRepository->find(new GameId($gameId));

        $this->assertTrue($game !== null);

        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testLocalNotDefined()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = 'NOT EXISTING';
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = 5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$NO_LOCAL);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);

    }

    public function testNegativeBet()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = -5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$BET_IS_NULL_OR_NEGATIVE);
        }

        try {
            $command->bet = 0;
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$BET_IS_NULL_OR_NEGATIVE);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);

    }

    public function testInsufficientUserCredits()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = 5000;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);

    }

    public function testNonExistingGuest()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = 'NON EXISTING ID';
        $command->bet = 5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$NO_VISITOR_FOUND);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }

    public function testNotOwnedLocalCube()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $notOwnedCube = $this->setCube('id', 'test', 'test', 0, 'test', false);

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $notOwnedCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = 5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$LOCAL_CUBE_NOT_OWNED);
            $this->cubeRepository->remove($notOwnedCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);

    }

    public function testSameLocalAndVisitor()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $localCube = $this->setLocalCube();

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $local->getId();
        $command->bet = 5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$SAME_VISITOR_AND_LOCAL);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);

    }

    public function testVisitorAndLocalAlreadyPlaying()
    {
        $command = new CreateGameCommand();
        $local = $this->setLocal();
        $visitor = $this->setVisitor();
        $localCube = $this->setLocalCube();
        $visitorCube = $this->setVisitorCube();
        $game = $this->getGame('test', $local, $visitor, $localCube, $visitorCube, 5, false);

        $command->response = new Response();
        $command->local = (string) $local->getId();
        $command->localCubeId = (string) $localCube->getId();
        $command->guest = (string) $visitor->getId();
        $command->bet = 5;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$LOCAL_AND_VISITOR_ALREADY_PLAYING);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($visitorCube);
            $this->cubeRepository->remove($localCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            return;
        }
        $this->assertTrue(false);
    }
}
