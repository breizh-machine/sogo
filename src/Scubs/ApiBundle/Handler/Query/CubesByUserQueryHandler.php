<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\ApiBundle\Query\CubesByUserQuery;
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
            $allCubeViews->add($this->cubeViewRenderer->renderView($cube));
        }

        $allRewardedCubes = $this->rewardRepository->findRewardsByUser($query->userId);
        foreach ($allRewardedCubes as $reward)
        {
            $allCubeViews->add($this->cubeViewRenderer->renderView($reward->getCube()));
        }

        return $allCubeViews;
    }
}