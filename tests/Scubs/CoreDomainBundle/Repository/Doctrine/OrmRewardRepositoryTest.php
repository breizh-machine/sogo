<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomainBundle\Entity\Reward;

class OrmRewardRepositoryTest extends BaseOrmRepository
{
    public function testAdd()
    {
        $this->init('reward_repository.doctrine_orm');
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $reward = new Reward(new RewardId(), $cube);
        $reward2 = new Reward(new RewardId(), $cube);
        $this->repository->add($reward);
        $this->repository->add($reward2);
        $this->repository->remove($reward);
        $this->assertTrue($this->repository->findAll() > 0);
    }
}