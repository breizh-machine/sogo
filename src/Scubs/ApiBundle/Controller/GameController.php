<?php

namespace Scubs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Scubs\ApiBundle\Command\CreateGameCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View;
use Scubs\ApiBundle\Query\GamesQuery;
use Scubs\ApiBundle\Query\GameQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Scubs\ApiBundle\Command\JoinGameCommand;
use Scubs\ApiBundle\Command\DenyInvitationCommand;
use Scubs\ApiBundle\Command\PlayTurnCommand;

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

    public function createGameAction(Request $request, $userId)
    {
        $command = new CreateGameCommand();
        $command->response = new JsonResponse(null, Response::HTTP_CREATED);
        $jsonBody = json_decode($request->getContent(), true);

        $command->bet = $jsonBody['bet'];
        $command->local = $userId;
        $command->localCubeId = $jsonBody['localCubeId'];
        $command->guest =isset($jsonBody['guest']) ? $jsonBody['guest'] : null;

        $handler = $this->get('scubs.api.handler.command.game.create');
        $handler->handle($command);

        return $command->response;
    }

    public function joinGameAction(Request $request, $gameId, $userId)
    {
        $command = new JoinGameCommand();

        $command->gameId = $gameId;
        $command->visitorId = $userId;
        $command->visitorCubeId = $request->request->get('visitorCubeId');

        $handler = $this->get('scubs.api.handler.command.game.join');
        $handler->handle($command);

        return new Response('', Response::HTTP_ACCEPTED);
    }

    public function denyGameAction($gameId, $userId)
    {
        $command = new DenyInvitationCommand();

        $command->gameId = $gameId;
        $command->visitorId = $userId;

        $handler = $this->get('scubs.api.handler.command.game.deny');
        $handler->handle($command);

        return new Response('', Response::HTTP_ACCEPTED);
    }

    public function playTurnAction($gameId, $userId, $x, $y, $z)
    {
        $command = new PlayTurnCommand();

        $command->gameId = $gameId;
        $command->playerId = $userId;
        $command->x = $x;
        $command->y = $y;
        $command->z = $z;

        $handler = $this->get('scubs.api.handler.command.game.play');
        $handler->handle($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }




}