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

    /*
    //Check that the visitor exists
if ($visitor === null) {
throw new GameLogicException(GameLogicException::$NO_VISITOR_FOUND_MESS, GameLogicException::$NO_VISITOR_FOUND);
}

//Check that the user has enough credits to bet
if ($visitor->getCredits() < $message->bet) {
    throw new GameLogicException(GameLogicException::$INSUFFICIENT_CREDITS_TO_BET_MESS, GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
}

//Check that the game exists
if ($game === null) {
    throw new GameLogicException(GameLogicException::$GAME_NOT_FOUND_MESS, GameLogicException::$GAME_NOT_FOUND);
}

//Check that the visitor is different than the local
if ($game->getLocal()->equals($visitor)) {
    throw new GameLogicException(GameLogicException::$SAME_VISITOR_AND_LOCAL_MESS, GameLogicException::$SAME_VISITOR_AND_LOCAL);
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
*/
    
}