<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Reward\RewardRepository;
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
    private $rewardRepository;
    
    public function __construct(GameRepository $gameRepository, CubeRepository $cubeRepository, UserProvider $userProvider, Router $router, RewardRepository $rewardRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->cubeRepository = $cubeRepository;
        $this->userProvider = $userProvider;
        $this->rewardRepository = $rewardRepository;
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
            $guest = $this->userProvider->loadUserById($message->guest);
            $game->inviteVisitor($guest);
        }
        $this->gameRepository->add($game);
        $message->response->headers->set('Location', $this->router->generate('scubs_api.game', [
            'userId' => (string) $local->getId(),
            'gameId' => (string) $game->getId()
        ]));
    }

    private function validate($message)
    {
        $local = $this->userProvider->loadUserById($message->local);
        $localCube = $this->cubeRepository->find(new CubeId($message->localCubeId));

        //Check that the local is defined
        if ($local === null) {
            throw new GameLogicException(GameLogicException::$NO_LOCAL_MESS, GameLogicException::$NO_LOCAL);
        }

        //Check that bet is positive
        if ($message->bet <= 0) {
            throw new GameLogicException(GameLogicException::$BET_IS_NULL_OR_NEGATIVE_MESS, GameLogicException::$BET_IS_NULL_OR_NEGATIVE);
        }

        //Check that the user has enough credits to bet
        if ($local->getCredits() < $message->bet) {
            throw new GameLogicException(GameLogicException::$INSUFFICIENT_CREDITS_TO_BET_MESS, GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
        }
        
        //If guest is set, check guest exists
        if ($message->guest && $this->userProvider->loadUserById($message->guest) === null) {
            throw new GameLogicException(GameLogicException::$NO_VISITOR_FOUND_MESS, GameLogicException::$NO_VISITOR_FOUND);
        }
        
        //Check that the user has the cube he wants to play with
        if (!$localCube->isNative() && count($this->rewardRepository->findRewardByCubeAndUser($message->local, $message->localCubeId)) < 1) {
            throw new GameLogicException(GameLogicException::$LOCAL_CUBE_NOT_OWNED_MESS, GameLogicException::$LOCAL_CUBE_NOT_OWNED);
        }

        //Check that the guest is different than the local
        if ($message->guest && $message->guest == $message->local) {
            throw new GameLogicException(GameLogicException::$SAME_VISITOR_AND_LOCAL_MESS, GameLogicException::$SAME_VISITOR_AND_LOCAL);
        }

        //Check that the local and the guest have'nt already a game running
        if ($message->guest && count($this->gameRepository->findAllNotEndedByPlayerCouple($message->local, $message->guest)) > 0 ) {
            throw new GameLogicException(GameLogicException::$LOCAL_AND_VISITOR_ALREADY_PLAYING_MESS, GameLogicException::$LOCAL_AND_VISITOR_ALREADY_PLAYING);
        }
    }
}