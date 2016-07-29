<?php

namespace Scubs\ApiBundle\Handler\Query;

use FOS\RestBundle\View\View;
use Scubs\ApiBundle\ViewDataAggregator\CubeViewDataAggregator;
use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\ApiBundle\Query\CubesQuery;
use Doctrine\Common\Collections\ArrayCollection;
use Scubs\ApiBundle\ViewRenderer\ViewRenderer;

class CubesQueryHandler
{
    private $cubeRepository;
    private $cubeViewRenderer;

    public function __construct(CubeRepository $cubeRepository, ViewRenderer $cubeViewRenderer)
    {
        $this->cubeRepository = $cubeRepository;
        $this->cubeViewRenderer = $cubeViewRenderer;
    }

    public function handle(CubesQuery $query)
    {
        $allCubes = $this->cubeRepository->findAll();
        $allCubeViews = new ArrayCollection();
        foreach ($allCubes as $cube)
        {
            $data = new CubeViewDataAggregator($cube);
            $allCubeViews->add($this->cubeViewRenderer->renderView($data));
        }

        return new View($allCubeViews);
    }
}