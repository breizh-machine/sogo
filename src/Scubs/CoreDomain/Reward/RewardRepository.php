<?php

namespace Scubs\CoreDomain\Reward;

use Scubs\CoreDomain\Core\ResourceRepository;

interface RewardRepository extends ResourceRepository
{
    public function findRewardByCubeAndUser($userId, $cubeId);
    public function findRewardsByUser($userId);
    public function findRewardByGame($gameId);
}