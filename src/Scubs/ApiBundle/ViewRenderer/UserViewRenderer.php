<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Scubs\ApiBundle\View\UserView;
use Scubs\ApiBundle\ViewDataAggregator\UserViewDataAggregator;
use Scubs\ApiBundle\ViewDataAggregator\ViewDataAggregator;
use Symfony\Component\Asset\Packages;

class UserViewRenderer
{
    private $assetsHelper;
    private $profileImagesPath;

    public function __construct(Packages $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
        $this->profileImagesPath = PathConfiguration::$PROFILE_IMAGE_PATH;
    }

    public function renderView(ViewDataAggregator $viewDataAggregator)
    {
        if ($viewDataAggregator instanceof UserViewDataAggregator) {
            
            $userView = new UserView();
            $user = $viewDataAggregator->getUser();
            $userView->id = (string) $user->getId();
            $userView->username = $user->getUsername();
            $userView->profilePicture = $user->getProfilePicture();
            $userView->credits = $user->getCredits();
            //TODO Create helper for this
            if (!PathConfiguration::isFullUrl($userView->profilePicture)) {
                $userView->profilePicture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->profileImagesPath, $userView->profilePicture));
            }
            return $userView;
        } else {
            //TODO error
        }
    }

}