<?php

namespace Scubs\ApiBundle\ViewDataAggregator;

use Doctrine\Common\Collections\Collection;

class PlayersViewDataAggregator implements ViewDataAggregator
{
    protected $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}