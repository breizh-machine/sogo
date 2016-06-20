<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\CoreDomainBundle\Entity\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Scubs\CoreDomain\Cube\CubeId;

class CreateGameCommandHandler implements MessageBus
{
    private $gameRepository;
    private $cubeRepository;
    private $userProvider;
    private $router;
    
    public function __construct(GameRepository $gameRepository, CubeRepository $cubeRepository, UserProvider $userProvider, Router $router)
    {
        $this->gameRepository = $gameRepository;
        $this->cubeRepository = $cubeRepository;
        $this->userProvider = $userProvider;
        $this->router = $router;
    }

    public function handle($message)
    {
        //Validate command
        $this->validate($message);

        $local = $this->userProvider->loadUserById($message->local);
        $localCube = $this->cubeRepository->find(new CubeId($message->localCubeId));
        $game = new Game(new GameId(), $local, $message->bet, $localCube);
        if ($message->guest) {
            $game->inviteVisitor($message->guest);
        }
        $this->gameRepository->add($game);
        $message->response->headers->set('Location', $this->router->generate('scubs_api.game', [
            'userId' => (string) $local->getId(),
            'gameId' => (string) $game->getId()
        ]));
    }

    private function validate($message)
    {
        //TODO
    }
}