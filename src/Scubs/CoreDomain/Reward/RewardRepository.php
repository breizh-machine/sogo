<?php

namespace Scubs\CoreDomain\Reward;

use Scubs\CoreDomain\Core\ResourceRepository;

interface RewardRepository extends ResourceRepository
{
    public function findRewardByCubeAndUser($userId, $cubeId);
    public function findRewardsByUser($userId, $q = null);
    public function findRewardByGame($gameId);
}