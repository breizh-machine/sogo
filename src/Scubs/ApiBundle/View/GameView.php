<?php

namespace Scubs\ApiBundle\View;

class GameView
{
    public $id;
    public $opponentProfilePicture;
    public $myProfilePicture;
    public $amILocal;
    public $didIWon;
    public $isStarted;
    public $isEnded;
    public $isMyTurn;
    public $turns;
    public $winningTurns;
    public $myCubeThumbnail;
    public $opponentCubeThumbnail;
    public $nbPlayedTurns;
    public $gameStartDate;
    public $lastTurnStartDate;
    public $rewardDescription;
    public $rewardRarity;
    public $rewardCubeThumbnail;
    public $isNewReward;
    public $bet;
    public $newCreditsValue;
    public $localCubeTexture;
    public $visitorCubeTexture;
    public $gameboardTexture;
    public $joinable;
    public $playable;
}