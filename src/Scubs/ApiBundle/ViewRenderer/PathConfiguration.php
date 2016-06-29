<?php

namespace Scubs\ApiBundle\ViewRenderer;

class PathConfiguration
{
    public static $PROFILE_IMAGE_PATH = 'bundles/scubscoredomain/profile/images';
    public static $CUBE_IMAGE_PATH = 'bundles/scubscoredomain/cube/images';

    public static function isFullUrl($url) {
        return preg_match('/http:\/\//', $url);
    }

}