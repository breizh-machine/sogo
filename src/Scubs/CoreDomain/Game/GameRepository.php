<?php

namespace Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Core\ResourceRepository;

interface GameRepository extends ResourceRepository
{
    public function findAllByUserIdOrderedByDate($userId);
}