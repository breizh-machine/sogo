<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Scubs\ApiBundle\ViewDataAggregator\PlayersViewDataAggregator;
use Scubs\ApiBundle\ViewDataAggregator\PlayerViewDataAggregator;
use Scubs\ApiBundle\ViewDataAggregator\ViewDataAggregator;
use Scubs\ApiBundle\View\PlayerView;
use Symfony\Component\Asset\Packages;
use Scubs\CoreDomain\User\User;

class PlayerViewRenderer implements ViewRenderer
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
        if ($viewDataAggregator instanceof PlayerViewDataAggregator) {
            return $this->renderSinglePlayerView($viewDataAggregator);
        } else if ($viewDataAggregator instanceof PlayersViewDataAggregator) {
            return $this->renderMultiplePlayersView($viewDataAggregator);
        } else {
            //TODO error
        }

    }

    private function getPlayerView(User $user)
    {
        $playerView = new PlayerView();
        $playerView->id = (string) $user->getId();
        $playerView->username = $user->getUsername();
        $playerView->profilePicture = $user->getProfilePicture();

        if (!PathConfiguration::isFullUrl($playerView->profilePicture)) {
            $playerView->profilePicture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->profileImagesPath, $playerView->profilePicture));
        }

        return $playerView;
    }

    private function renderSinglePlayerView(PlayerViewDataAggregator $viewDataAggregator)
    {
        return $this->getPlayerView($viewDataAggregator->getUser());
    }

    private function renderMultiplePlayersView(PlayersViewDataAggregator $viewDataAggregator)
    {
        $playersView = [];
        $users = $viewDataAggregator->getUsers();
        foreach ($users as $user) {
            $playersView[] = $this->getPlayerView($user);
        }
        return $playersView;
    }

}