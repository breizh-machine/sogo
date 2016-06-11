<?php

namespace Scubs\CoreDomainBundle\Repository;

use Scubs\CoreDomain\Reward\Reward;
use Scubs\CoreDomain\Reward\RewardId;
use Scubs\CoreDomain\Reward\RewardRepository;

class InMemoryRewardRepository extends InMemoryResourceRepository implements RewardRepository
{
    private $cubes;
    private $cubeRepository;

    public function __construct()
    {
        parent::__construct('Scubs\CoreDomain\Reward\Reward', 'Scubs\CoreDomain\Reward\RewardId');
        
        $this->cubeRepository = new InMemoryCubeRepository();
        $this->cubes = $this->cubeRepository->findAll();

        $this->resources->add(new Reward(
            new RewardId('8CE05088-ED1F-43E9-A415-3B3792655A9B'), $this->cubes[0], new \DateTime()
        ));
        $this->resources->add(new Reward(
            new RewardId('62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23'), $this->cubes[1], new \DateTime()
        ));
    }
}