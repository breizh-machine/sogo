<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\GamesQuery;
use Scubs\ApiBundle\Query\Query;
use Scubs\ApiBundle\ViewRenderer\GameViewRenderer;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;
use Scubs\CoreDomain\Game\GameId;
use Scubs\ApiBundle\Query\GameQuery;

class GamesQueryHandler implements QueryHandler
{
    private $gameRepository;
    private $gameViewRenderer;
    private $userProvider;

    public function __construct(GameRepository $gameRepository, GameViewRenderer $gameViewRenderer, UserProvider $userProvider)
    {
        $this->gameRepository = $gameRepository;
        $this->gameViewRenderer = $gameViewRenderer;
        $this->userProvider = $userProvider;
    }

    public function handle(Query $query)
    {
        $this->validate($query);

        //TODO - Handle pagination
        $authenticatedUser = $this->userProvider->loadUserById($query->userId);

        if ($query instanceof GamesQuery) {
            $games = $this->gameRepository->findAllByUserIdOrderedByDate($query->userId);
            return $this->gameViewRenderer->renderView($games, $authenticatedUser);
        } else if ($query instanceof GameQuery) {
            $game = $this->gameRepository->find(new GameId($query->gameId));
            $view = $this->gameViewRenderer->renderView($game, $authenticatedUser);
            return $view;
        } else {
            //TODO
        }
    }
    
    public function validate(Query $query)
    {
        //TODO
    }
}