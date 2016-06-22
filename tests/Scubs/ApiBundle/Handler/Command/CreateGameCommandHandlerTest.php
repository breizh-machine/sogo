<?php

namespace Scubs\ApiBundle\Handler\Command;

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

}
