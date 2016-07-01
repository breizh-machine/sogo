<?php

namespace Scubs\ApiBundle\ViewDataAggregator;

use Scubs\CoreDomainBundle\Entity\User;

class UserViewDataAggregator implements ViewDataAggregator
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}