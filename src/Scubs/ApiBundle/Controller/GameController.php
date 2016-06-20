<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Scubs\ApiBundle\Command\CreateGameCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\GamesQuery;
use Scubs\ApiBundle\Query\GameQuery;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * @Rest\View
     */
    public function allAction($userId)
    {
        $query = new GamesQuery();
        $query->userId = $userId;
        $handler = $this->get('scubs.api.handler.query.games');
        return $handler->handle($query);
    }

    /**
     * @Rest\View
     */
    public function gameAction($userId, $gameId)
    {
        $query = new GameQuery();
        $query->userId = $userId;
        $query->gameId = $gameId;
        $handler = $this->get('scubs.api.handler.query.games');
        $view = $handler->handle($query);
        return $view;
    }

    public function createGameAction($userId)
    {
        $response = new Response();

        $command = new CreateGameCommand();
        $command->bet = 5;
        $command->response = $response;
        $command->local = $userId;
        $command->localCubeId = '8bc905c1-40f3-4df6-a9c1-bd45232eb278';
        $handler = $this->get('scubs.api.handler.command.game');
        $handler->handle($command);

        return $response;
    }


}