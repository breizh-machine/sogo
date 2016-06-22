<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;
use SimpleBus\Message\Bus\MessageBus;

class DenyInvitationCommandHandler implements MessageBus
{
    private $gameRepository;
    private $cubeRepository;
    private $userProvider;

    public function __construct(GameRepository $gameRepository, CubeRepository $cubeRepository, UserProvider $userProvider)
    {
        $this->gameRepository = $gameRepository;
        $this->cubeRepository = $cubeRepository;
        $this->userProvider = $userProvider;
    }

    public function handle($message)
    {
        //Validate command
        $this->validate($message);

        $game = $this->gameRepository->find(new GameId($message->gameId));
        $local = $this->userProvider->loadUserById((string) $game->getLocal()->getId());
        $game->visitorDeclined();

        $this->userProvider->updateUser($local);
        $this->gameRepository->update($game);
    }

    private function validate($message)
    {
        $game = $this->gameRepository->find(new GameId($message->gameId));
        $visitor = $this->userProvider->loadUserById($message->visitorId);
        
        //Check that the game exists
        if ($game === null) {
            throw new GameLogicException(GameLogicException::$GAME_NOT_FOUND_MESS, GameLogicException::$GAME_NOT_FOUND);
        }

        //Check that the visitor exists
        if ($visitor === null) {
            throw new GameLogicException(GameLogicException::$NO_VISITOR_FOUND_MESS, GameLogicException::$NO_VISITOR_FOUND);
        }
        
        //Check that the visitor is the game visitor
        if ($game->getVisitor() === null || !$visitor->equals($game->getVisitor())) {
            throw new GameLogicException(GameLogicException::$VISITOR_NOT_IN_GAME_MESS, GameLogicException::$VISITOR_NOT_IN_GAME);
        }

        //Check that the visitor didn't already decline
        if ($game->isVisitorDeclined()) {
            throw new GameLogicException(GameLogicException::$VISITOR_ALREADY_DECLINED_MESS, GameLogicException::$VISITOR_ALREADY_DECLINED);
        }

        //Check that the game didn't already started
        if ($game->isGameStarted()) {
            throw new GameLogicException(GameLogicException::$GAME_ALREADY_STARTED_MESS, GameLogicException::$GAME_ALREADY_STARTED);
        }
    }
}

