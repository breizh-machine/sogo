<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Scubs\ApiBundle\ViewDataAggregator\ViewDataAggregator;
use Symfony\Component\Asset\Packages;
use Scubs\ApiBundle\View\CubeView;

class CubeViewRenderer implements ViewRenderer
{
    private $assetsHelper;
    private $cubeImagesBasePath;

    public function __construct(Packages $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
        $this->cubeImagesBasePath = PathConfiguration::$CUBE_IMAGE_PATH;
    }

    public function renderView(ViewDataAggregator $data)
    {
        $cubeView = new CubeView();
        $data = $data->getCube();
        $cubeView->id = (string) $data->getId();
        $cubeView->description = $data->getDescription();
        $cubeView->rarity = $data->getRarity();
        $cubeView->textureUrl = $data->getTexture();
        $cubeView->thumbnailUrl = $data->getThumbnail();
        if (!PathConfiguration::isFullUrl($cubeView->thumbnailUrl)) {
            $cubeView->thumbnailUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $data->getThumbnail()));
        }
        if (!PathConfiguration::isFullUrl($cubeView->textureUrl)) {
            $cubeView->textureUrl = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $data->getTexture()));
        }
        
        return $cubeView;
    }
}