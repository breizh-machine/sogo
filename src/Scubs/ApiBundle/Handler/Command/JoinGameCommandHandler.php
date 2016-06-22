<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Reward\RewardRepository;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;
use SimpleBus\Message\Bus\MessageBus;
use Scubs\CoreDomain\Cube\CubeId;

class JoinGameCommandHandler implements MessageBus
{
    private $gameRepository;
    private $cubeRepository;
    private $userProvider;
    private $rewardRepository;

    public function __construct(GameRepository $gameRepository, CubeRepository $cubeRepository, UserProvider $userProvider, RewardRepository $rewardRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->cubeRepository = $cubeRepository;
        $this->userProvider = $userProvider;
        $this->rewardRepository = $rewardRepository;
    }

    public function handle($message)
    {
        //Validate command
        $this->validate($message);

        $game = $this->gameRepository->find(new GameId($message->gameId));
        $visitor = $this->userProvider->loadUserById($message->visitorId);
        $visitorCube = $this->cubeRepository->find(new CubeId($message->visitorCubeId));

        if (!$game->isVisitorJoined()) {
            $game->inviteVisitor($visitor);
        }
        $game->visitorJoined($visitorCube);
        $this->userProvider->updateUser($visitor);

        $this->gameRepository->update($game);

    }

    private function validate($message)
    {
        $visitor = $this->userProvider->loadUserById($message->visitorId);
        $visitorCube = $this->cubeRepository->find(new CubeId($message->visitorCubeId));
        $game = $this->gameRepository->find(new GameId($message->gameId));

        //Check that the visitor exists
        if ($visitor === null) {
            throw new GameLogicException(GameLogicException::$NO_VISITOR_FOUND_MESS, GameLogicException::$NO_VISITOR_FOUND);
        }

        //Check that the game exists
        if ($game === null) {
            throw new GameLogicException(GameLogicException::$GAME_NOT_FOUND_MESS, GameLogicException::$GAME_NOT_FOUND);
        }

        //Check that the user has enough credits to bet
        if ($visitor->getCredits() < $game->getBet()) {
            throw new GameLogicException(GameLogicException::$INSUFFICIENT_CREDITS_TO_BET_MESS, GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
        }

        //Check that the visitor is different than the local
        if ($game->getLocal()->equals($visitor)) {
            throw new GameLogicException(GameLogicException::$SAME_VISITOR_AND_LOCAL_MESS, GameLogicException::$SAME_VISITOR_AND_LOCAL);
        }

        //Check that the visitor who wants to join is the one set in the game
        if ($game->getVisitor() != null && !$game->getVisitor()->equals($visitor)) {
            throw new GameLogicException(GameLogicException::$VISITOR_NOT_ALLOWED_TO_JOIN_MESS, GameLogicException::$VISITOR_NOT_ALLOWED_TO_JOIN);
        }
        
        //Check the game did'nt start already
        if ($game->isVisitorJoined()) {
            throw new GameLogicException(GameLogicException::$VISITOR_ALREADY_SET_MESS, GameLogicException::$VISITOR_ALREADY_SET);
        }

        //Check that visitor cube exists
        if ($visitorCube === null) {
            throw new GameLogicException(GameLogicException::$NO_VISITOR_CUBE_FOUND_MESS, GameLogicException::$NO_VISITOR_CUBE_FOUND);
        }

        //Check that the user has the cube he wants to play with
        if (!$visitorCube->isNative() && count($this->rewardRepository->findRewardByCubeAndUser($message->visitorId, $message->visitorCubeId)) < 1) {
            throw new GameLogicException(GameLogicException::$LOCAL_CUBE_NOT_OWNED_MESS, GameLogicException::$LOCAL_CUBE_NOT_OWNED);
        }
    }
}

