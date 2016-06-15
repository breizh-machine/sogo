<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Symfony\Component\Asset\Packages;
use Scubs\ApiBundle\View\CubeView;

class CubeViewRenderer implements ViewRenderer
{
    private $assetsHelper;
    private $cubeImagesBasePath;

    public function __construct(Packages $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
        $this->cubeImagesBasePath = 'bundles/scubscoredomain/cube/images';
    }

    public function renderView($data)
    {
        $cubeView = new CubeView();
        $cubeView->id = (string) $data->getId();
        $cubeView->description = $data->getDescription();
        $cubeView->rarity = $data->getRarity();
        $cubeView->textureUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $data->getTexture()));
        $cubeView->thumbnailUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $data->getThumbnail()));
        return $cubeView;
    }
}