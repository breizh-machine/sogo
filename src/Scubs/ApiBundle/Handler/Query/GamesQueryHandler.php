<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\Query;
use Scubs\ApiBundle\ViewRenderer\GameViewRenderer;
use Scubs\CoreDomain\Game\GameRepository;

class GamesQueryHandler implements QueryHandler
{
    private $gameRepository;
    private $gameViewRenderer;

    public function __construct(GameRepository $gameRepository, GameViewRenderer $gameViewRenderer)
    {
        $this->gameRepository = $gameRepository;
        $this->gameViewRenderer = $gameViewRenderer;
    }

    public function handle(Query $query)
    {
        $this->validate($query);

        $authenticatedUser = $this->userProvider->findById($query->userId);
        $games = $this->gameRepository->findAllByUserIdOrderedByDate($query->userId);

        return $this->gameViewRenderer->renderView($games, $authenticatedUser);
    }
    
    public function validate(Query $query)
    {
        //TODO
    }
}