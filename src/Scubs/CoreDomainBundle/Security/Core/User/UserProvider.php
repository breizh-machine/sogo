<?php

namespace Scubs\CoreDomainBundle\Security\Core\User;

use FOS\UserBundle\Security\UserProvider as BaseUserProvider;

use FOS\UserBundle\Model\UserManagerInterface;

class UserProvider extends BaseUserProvider
{
    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        parent::__construct($userManager);
    }

    public function loadUserById($id)
    {
        return $this->userManager->findUserBy(['id' => $id]);
    }
}