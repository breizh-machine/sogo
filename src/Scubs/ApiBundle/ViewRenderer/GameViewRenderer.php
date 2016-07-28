<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Scubs\ApiBundle\View\GameListItemView;
use Scubs\ApiBundle\View\GameView;
use Scubs\ApiBundle\View\TurnView;
use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Player\ScubPlayer;
use Scubs\CoreDomain\Reward\Reward;
use Scubs\CoreDomain\User\User;
use Symfony\Component\Asset\Packages;

class GameViewRenderer implements ViewRenderer
{
    private $assetsHelper;
    private $profileImagesPath;
    private $cubeImagesBasePath;

    public function __construct(Packages $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
        $this->profileImagesPath = PathConfiguration::$PROFILE_IMAGE_PATH;
        $this->cubeImagesBasePath = PathConfiguration::$CUBE_IMAGE_PATH;
    }

    public function renderView($gameData, ScubPlayer $authenticatedPlayer, Reward $reward = null)
    {
        $renderedData = null;
        if ($gameData instanceof Collection || is_array($gameData)) {
            $renderedData = new ArrayCollection();
            foreach ($gameData as $game) {
                $renderedData->add($this->renderGameListItemView($game, $authenticatedPlayer));
            }
        } else {
            $renderedData = $this->renderGameView($gameData, $authenticatedPlayer, $reward);
        }
        return $renderedData;
    }

    private function renderGameListItemView(Game $game, ScubPlayer $authenticatedPlayer)
    {
        $view = new GameListItemView();
        $opponent = $game->getLocal()->equals($authenticatedPlayer) ? $game->getVisitor() : $game->getLocal();

        $view->id = $game->getId();
        $view->opponentProfilePicture = $opponent !== null ? $opponent->getProfilePicture() : '';
        $view->opponentName = $opponent !== null ? $opponent->getUsername() : '';
        $view->hasVisitorJoined = $game->isVisitorJoined();
        $view->hasVisitorDeclined = $game->isVisitorDeclined();
        $view->won = $game->getWinner() !== null && $game->getWinner()->equals($authenticatedPlayer);
        $view->lost = $game->getWinner() !== null && $game->getWinner()->equals($opponent);
        $view->bet = $game->getBet();
        //$view->cubeWonThumbnail = $view->won ? $game->getReward()->getCube()->getThumbnail() : null;
        $view->nbTurnsPlayed = count($game->getTurns());
        $view->lastTurnPlayedDate = $game->getLastTurn() !== null ? $game->getLastTurn()->getStartDate()->format('d m Y') : '';
        $view->gameStartDate = $game->getLastTurn() !== null ? $game->getLastTurn()->getStartDate()->format('d m Y') : '';
        $view->joinable = !$authenticatedPlayer->equals($game->getLocal()) && !$game->isVisitorJoined();
        $view->playable = $game->isScubPlayerTurn($authenticatedPlayer) && $game->isVisitorJoined() && !$game->isGameEnded();

        return $view;
    }

    private function renderGameView(Game $game, ScubPlayer $authenticatedPlayer, Reward $reward = null)
    {
        $gameView = new GameView();
        $gameView->gameboardTexture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, 'gameboard.jpg'));
        $gameView->id = (string) $game->getId();
        $gameView->amILocal = $game->getLocal()->equals($authenticatedPlayer);

        $visitorProfilePicture = $game->getVisitor() !== null ? $game->getVisitor()->getProfilePicture() : User::getDefaultProfilePicture();
        $gameView->opponentProfilePicture = $gameView->amILocal ? $visitorProfilePicture : $game->getLocal()->getProfilePicture();
        if (!PathConfiguration::isFullUrl($gameView->opponentProfilePicture)) {
            $gameView->opponentProfilePicture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->profileImagesPath, $gameView->opponentProfilePicture));
        }

        $gameView->myProfilePicture = $gameView->amILocal ? $game->getLocal()->getProfilePicture() : $visitorProfilePicture;
        if (!PathConfiguration::isFullUrl($gameView->myProfilePicture)) {
            $gameView->myProfilePicture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->profileImagesPath, $gameView->myProfilePicture));
        }

        $gameView->didIWon = $game->isGameEnded() && $game->getWinner()->equals($authenticatedPlayer);
        $gameView->isStarted = $game->isGameStarted();
        $gameView->isEnded = $game->isGameEnded();
        $gameView->isMyTurn = $game->isScubPlayerTurn($authenticatedPlayer);
        $gameView->winningTurns = $game->isGameEnded() ? $game->getWinningTurns() : [];

        $visitorCubeThumbnail = $game->getVisitorCube() !== null ? $game->getVisitorCube()->getThumbnail() : null;
        $gameView->myCubeThumbnail = $gameView->amILocal ? $game->getLocalCube()->getThumbnail() : $visitorCubeThumbnail;
        if (!PathConfiguration::isFullUrl($gameView->myCubeThumbnail)) {
            $gameView->myCubeThumbnail = $gameView->myCubeThumbnail !== null ? $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $gameView->myCubeThumbnail)) : '';
        }

        $gameView->opponentCubeThumbnail = $gameView->amILocal ? $visitorCubeThumbnail : $game->getLocalCube()->getThumbnail();
        if (!PathConfiguration::isFullUrl($gameView->opponentCubeThumbnail)) {
            $gameView->opponentCubeThumbnail = $gameView->opponentCubeThumbnail !== null ? $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $gameView->opponentCubeThumbnail)) : '';
        }

        $gameView->nbPlayedTurns = count($game->getTurns());
        $gameView->gameStartDate = $game->getStartDate()->format(\DateTime::ISO8601);
        $gameView->lastTurnStartDate = $game->getLastTurn() !== null ? $game->getLastTurn()->getStartDate()->format(\DateTime::ISO8601) : '';
        $gameView->rewardDescription = $reward !== null ? $reward->getCube()->getDescription() : '';
        $gameView->rewardRarity = $reward !== null ? $reward->getCube()->getRarity() : '';
        $gameView->rewardCubeThumbnail = $reward !== null ? $reward->getCube()->getThumbnail() : '';
        $gameView->bet = $game->getBet();
        $gameView->newCreditsValue = $game->getWinner() !== null ? $game->getWinner()->getCredits() : '';
        $gameView->turns = [];

        $gameView->localCubeTexture = $game->getLocalCube()->getTexture();
        if (!PathConfiguration::isFullUrl($gameView->localCubeTexture)) {
            $gameView->localCubeTexture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $game->getLocalCube()->getTexture()));
        }
        if ($game->getVisitorCube() !== null) {
            $gameView->visitorCubeTexture = $game->getVisitorCube()->getTexture();
            if (!PathConfiguration::isFullUrl($gameView->visitorCubeTexture)) {
                $gameView->visitorCubeTexture = $this->assetsHelper->getUrl(sprintf('%s/%s', $this->cubeImagesBasePath, $game->getVisitorCube()->getTexture()));
            }
        }
        $gameView->joinable = !$authenticatedPlayer->equals($game->getLocal()) && !$game->isVisitorJoined();
        $gameView->playable = $game->isScubPlayerTurn($authenticatedPlayer) && $game->isVisitorJoined() && !$game->isGameEnded();

        foreach ($game->getTurns() as $turn) {
            $turnView = new TurnView();
            $turnView->x = $turn->getX();
            $turnView->y = $turn->getY();
            $turnView->z = $turn->getZ();
            $turnView->isLocalTurn = $game->getLocal()->equals($turn->getPlayer());
            $gameView->turns[] = $turnView;
        }
        
        return $gameView;
    }
    
}