<?php

namespace Scubs\ApiBundle\Handler\Query;

use FOS\RestBundle\View\View;
use Scubs\ApiBundle\Query\CubesByUserQuery;
use Scubs\ApiBundle\ViewDataAggregator\CubeViewDataAggregator;
use Scubs\CoreDomain\Cube\CubeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Scubs\ApiBundle\ViewRenderer\ViewRenderer;
use Scubs\CoreDomain\Reward\RewardRepository;

class CubesByUserQueryHandler
{
    private $cubeRepository;
    private $rewardRepository;
    private $cubeViewRenderer;

    public function __construct(CubeRepository $cubeRepository, RewardRepository $rewardRepository, ViewRenderer $cubeViewRenderer)
    {
        $this->cubeRepository = $cubeRepository;
        $this->rewardRepository = $rewardRepository;
        $this->cubeViewRenderer = $cubeViewRenderer;
    }

    public function handle(CubesByUserQuery $query)
    {
        $allCubeViews = new ArrayCollection();
        $allNativeCubes = $this->cubeRepository->getAllNativeCubes();
        foreach ($allNativeCubes as $cube)
        {
            $data = new CubeViewDataAggregator($cube);
            $allCubeViews->add($this->cubeViewRenderer->renderView($data));
        }

        $allRewardedCubes = $this->rewardRepository->findRewardsByUser($query->userId, $query->q);
        foreach ($allRewardedCubes as $reward)
        {
            $data = new CubeViewDataAggregator($reward->getCube());
            $allCubeViews->add($this->cubeViewRenderer->renderView($data));
        }

        return new View($allCubeViews);
    }
}