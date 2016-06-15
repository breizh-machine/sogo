<?php

namespace Scubs\ApiBundle\Handler\Query;

use Scubs\CoreDomain\Cube\CubeRepository;
use Scubs\ApiBundle\Query\CubesQuery;
use Scubs\ApiBundle\View\CubeView;
use Symfony\Component\Asset\Packages;
use Doctrine\Common\Collections\ArrayCollection;

class CubesQueryHandler
{
    private $cubeRepository;
    private $assetsHelper;

    public function __construct(CubeRepository $cubeRepository, Packages $assetsHelper)
    {
        $this->cubeRepository = $cubeRepository;
        $this->assetsHelper = $assetsHelper;
    }

    public function handle(CubesQuery $query)
    {
        $imagesBasePath = 'bundles/scubscoredomain/cube/images';
        
        $allCubes = $this->cubeRepository->findAll();
        $allCubeViews = new ArrayCollection();
        foreach ($allCubes as $cube)
        {
            $cubeView = new CubeView();
            $cubeView->id = (string) $cube->getId();
            $cubeView->description = $cube->getDescription();
            $cubeView->rarity = $cube->getRarity();
            $cubeView->textureUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $imagesBasePath, $cube->getTexture()));
            $cubeView->thumbnailUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $imagesBasePath, $cube->getThumbnail()));
            $allCubeViews->add($cubeView);
        }

        return $allCubeViews;
    }
}