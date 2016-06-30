<?php

namespace Scubs\ApiBundle\Handler\Query;

use Doctrine\Common\Collections\ArrayCollection;
use Scubs\ApiBundle\ViewDataAggregator\PlayersViewDataAggregator;
use Scubs\CoreDomain\User\UserRepository;
use Scubs\ApiBundle\Query\PlayersQuery;
use Scubs\ApiBundle\ViewRenderer\ViewRenderer;

class PlayersQueryHandler
{
    private $userRepository;
    private $playerViewRenderer;

    public function __construct(UserRepository $userRepository, ViewRenderer $playerViewRenderer)
    {
        $this->userRepository = $userRepository;
        $this->playerViewRenderer = $playerViewRenderer;
    }

    public function handle(PlayersQuery $query)
    {
        $users = $this->userRepository->getAvailablePlayersUsername($query->userId, $query->q);
        if (is_array($users)) {
            $users = new ArrayCollection($users);
        }
        $playersViewDataAggregator = new PlayersViewDataAggregator($users);
        return $this->playerViewRenderer->renderView($playersViewDataAggregator);
    }
}
