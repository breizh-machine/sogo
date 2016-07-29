<?php

namespace Scubs\ApiBundle\View;

class GameListItemView extends View
{
    public $id;
    public $opponentProfilePicture;
    public $opponentName;
    public $hasVisitorJoined;
    public $hasVisitorDeclined;
    public $won;
    public $lost;
    public $bet;
    public $cubeWonThumbnail;
    public $nbTurnsPlayed;
    public $lastTurnPlayedDate;
    public $gameStartDate;
    public $joinable;
    public $playable;
}