<?php

namespace Scubs\ApiBundle\ViewRenderer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Scubs\ApiBundle\View\GameListItemView;
use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Player\ScubPlayer;

class GameViewRenderer implements ViewRenderer
{

    public function renderView($gameData, ScubPlayer $authenticatedPlayer)
    {
        $renderedData = null;
        if ($gameData instanceof Collection || is_array($gameData)) {
            $renderedData = new ArrayCollection();
            foreach ($gameData as $game) {
                $renderedData->add($this->renderGameListItemView($game, $authenticatedPlayer));
            }
        } else {
            $renderedData = $this->renderGameView($gameData, $authenticatedPlayer);
        }
        return $renderedData;
    }

    private function renderGameListItemView(Game $game, ScubPlayer $authenticatedPlayer)
    {
        $view = new GameListItemView();
        $opponent = $game->getLocal()->equals($authenticatedPlayer) ? $game->getVisitor() : $game->getLocal();

        $view->opponentProfilePicture = $opponent->getProfilePicture();
        $view->opponentName = $opponent->getUsername();
        $view->hasVisitorJoined = $game->isVisitorJoined();
        $view->hasVisitorDeclined = $game->isVisitorDeclined();
        $view->won = $game->getWinner() !== null && $game->getWinner()->equals($authenticatedPlayer);
        $view->lost = $game->getWinner() !== null && $game->getWinner()->equals($opponent);
        $view->bet = $game->getBet();
        $view->cubeWonThumbnail = $view->won ? $game->getReward()->getCube()->getThumbnail() : null;
        $view->nbTurnsPlayed = count($game->getTurns());
        $view->lastTurnPlayedDate = $game->getLastTurn()->getStartDate()->format('d m Y');
        $view->gameStartDate = $game->getLastTurn()->getStartDate()->format('d m Y');

        return $view;
    }

    private function renderGameView(Game $game, ScubPlayer $authenticatedPlayer)
    {
        //TODO
    }
    
}