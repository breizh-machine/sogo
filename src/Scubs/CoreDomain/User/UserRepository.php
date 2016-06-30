<?php

namespace Scubs\CoreDomain\User;

use Scubs\CoreDomain\Core\ResourceRepository;

interface UserRepository extends ResourceRepository
{
    public function getAvailablePlayersUsername($userId, $q = '');
}