<?php
namespace Scubs\ApiBundle\Handler\Command;

use Scubs\CoreDomain\Game\GameLogicException;
use Tests\Scubs\CoreDomainBundle\Repository\Doctrine\BaseOrmRepository;
use Scubs\ApiBundle\Command\PlayTurnCommand;

class PlayTurnCommandHandlerTest extends BaseOrmRepository
{
    protected $handler;

    public function setUp()
    {
        $this->init();
        $this->handler = $this->container->get('scubs.api.handler.command.game.play');
    }

    public function testOk()
    {
        $command = new PlayTurnCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $initialCredits = $local->getCredits();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $cubesByRarity = $this->setCubesForEachRarity();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25, false);

        $command->gameId = (string) $game->getId();
        $command->playerId = (string) $local->getId();
        $command->x = 3;
        $command->y = 3;
        $command->z = 0;

        $this->handler->handle($command);
        $reward = $this->rewardRepository->findRewardByGame((string) $game->getId());

        $this->assertTrue($reward !== null);
        $this->assertTrue($game->isGameEnded());
        $this->assertTrue($game->getWinner() != null);
        $this->assertTrue($game->getWinner()->equals($local));
        $this->assertTrue($initialCredits == ( $local->getCredits() - 25));

        $this->rewardRepository->remove($reward);
        $this->gameRepository->remove($game);
        $this->cubeRepository->remove($localCube);
        $this->cubeRepository->remove($visitorCube);
        foreach ($cubesByRarity as $cube) {
            $this->cubeRepository->remove($cube);
        }
        $this->userManager->deleteUser($visitor);
        $this->userManager->deleteUser($local);
    }

    public function testNonExistingGame()
    {
        $command = new PlayTurnCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25, false);

        $command->gameId = 'NOT EXISTING';
        $command->playerId = (string) $local->getId();
        $command->x = 3;
        $command->y = 3;
        $command->z = 0;

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


    public function testNonExistingVisitor()
    {
        $command = new PlayTurnCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25, false);

        $command->gameId = (string) $game->getId();
        $command->playerId = 'NOT EXISTING';
        $command->x = 3;
        $command->y = 3;
        $command->z = 0;

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

    public function testPlayerOutOfGame()
    {
        $command = new PlayTurnCommand();
        $visitor = $this->setVisitor();
        $local = $this->setLocal();
        $thirdPlayer = $this->setThirdPlayer();
        $visitorCube = $this->setVisitorCube();
        $localCube = $this->setLocalCube();
        $game = $this->getGame('id', $local, $visitor, $localCube, $visitorCube, 25, false);

        $command->gameId = (string) $game->getId();
        $command->playerId = (string) $thirdPlayer->getId();
        $command->x = 3;
        $command->y = 3;
        $command->z = 0;

        try {
            $this->handler->handle($command);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() == GameLogicException::$NOT_IN_GAME_PLAYER);
            $this->gameRepository->remove($game);
            $this->cubeRepository->remove($localCube);
            $this->cubeRepository->remove($visitorCube);
            $this->userManager->deleteUser($visitor);
            $this->userManager->deleteUser($local);
            $this->userManager->deleteUser($thirdPlayer);
            return;
        }
        $this->assertTrue(false);
    }

    /*
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

     */

}