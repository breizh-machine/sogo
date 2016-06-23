<?php

namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomain\Reward\RewardRepository;
use Scubs\CoreDomainBundle\Entity\Reward;
use Scubs\CoreDomainBundle\Entity\Turn;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;
use SimpleBus\Message\Bus\MessageBus;
use Scubs\CoreDomain\Turn\TurnId;
use Scubs\CoreDomain\Cube\CubeUtils;
use Scubs\CoreDomain\Reward\RewardId;

class PlayTurnCommandHandler implements MessageBus
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
        $player = $this->userProvider->loadUserById($message->playerId);
        $turn = new Turn(new TurnId(), $game, $player, $message->x, $message->y, $message->z);

        $game->play($turn);

        if ($game->getWinner() !== null) {
            $cubeRarity = CubeUtils::pickRandomRarity();
            $existingCubes = $this->cubeRepository->getCubesByRarity($cubeRarity);
            $cubeReward = $existingCubes[array_rand($existingCubes)];
            $reward = new Reward(new RewardId(), $cubeReward);
            $reward->assignToGame($game);
            $this->rewardRepository->add($reward);
        }
        $this->gameRepository->update($game);
    }

    private function validate($message)
    {
        $game = $this->gameRepository->find(new GameId($message->gameId));
        $player = $this->userProvider->loadUserById($message->playerId);

        //Check that the game exists
        if ($game === null) {
            throw new GameLogicException(GameLogicException::$GAME_NOT_FOUND_MESS, GameLogicException::$GAME_NOT_FOUND);
        }

        //Check that the player exists
        if ($player === null) {
            throw new GameLogicException(GameLogicException::$NO_VISITOR_FOUND_MESS, GameLogicException::$NO_VISITOR_FOUND);
        }

        //Check that the player is in this game
        if (!$player->equals($game->getVisitor()) && !$player->equals($game->getLocal())) {
            throw new GameLogicException(GameLogicException::$NOT_IN_GAME_PLAYER_MESS, GameLogicException::$NOT_IN_GAME_PLAYER);
        }

    }
}

