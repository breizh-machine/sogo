<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\Query;
use Scubs\ApiBundle\ViewRenderer\GameViewRenderer;
use Scubs\CoreDomain\Game\GameRepository;
use Scubs\CoreDomainBundle\Security\Core\User\UserProvider;

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

        $authenticatedUser = $this->userProvider->loadUserById($query->userId);
        $games = $this->gameRepository->findAllByUserIdOrderedByDate($query->userId);

        return $this->gameViewRenderer->renderView($games, $authenticatedUser);
    }
    
    public function validate(Query $query)
    {
        //TODO
    }
}