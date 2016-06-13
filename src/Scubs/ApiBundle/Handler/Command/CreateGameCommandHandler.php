<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomain\User\UserRepository;
use SimpleBus\Message\Bus\MessageBus;

class CreateGameCommandHandler implements MessageBus
{
    private $gameRepository;
    private $userRepository;
    
    public function __construct(GameRepository $gameRepository, UserRepository $userRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
    }

    public function handle($message)
    {
        //Validate command

        $local = $this->userRepository->find(new ResourceId($message->local));

        $game = new Game(new GameId(), $local, $message->bet, $message->localCubeId);
        if ($message->guest) {
            $game->inviteVisitor($message->guest);
        }
        $this->gameRepository->add($game);
    }
}