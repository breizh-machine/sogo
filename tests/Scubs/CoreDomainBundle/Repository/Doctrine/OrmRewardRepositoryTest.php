<?php

namespace Tests\Scubs\CoreDomainBundle\Repository\Doctrine;

use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomainBundle\Entity\Cube;
use Scubs\CoreDomain\Cube\CubeId;
use Scubs\CoreDomainBundle\Entity\Reward;

class OrmRewardRepositoryTest extends BaseOrmRepository
{
    private $cubeRepository;
    private $rewardRepository;

    public function setUp()
    {
        $this->init('cube_repository.doctrine_orm');
        $this->init('reward_repository.doctrine_orm');
        $this->cubeRepository = $this->repositories['cube_repository.doctrine_orm'];
        $this->rewardRepository = $this->repositories['reward_repository.doctrine_orm'];
    }

    public function testCrud()
    {
        $cube = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $cube2 = new Cube(new CubeId(), 'test', 'thumbnail', 0, 'description');
        $reward = new Reward(new RewardId('initial'), $cube);
        $reward2 = new Reward(new RewardId(), $cube);
        $this->rewardRepository->add($reward);
        $this->rewardRepository->add($reward2);
        $this->assertTrue(count($this->rewardRepository->findAll()) == 2);
        $this->rewardRepository->remove($reward);
        $this->assertTrue(count($this->rewardRepository->findAll()) == 1);
        $this->rewardRepository->remove($reward2);
        $this->cubeRepository->remove($cube);
    }
}