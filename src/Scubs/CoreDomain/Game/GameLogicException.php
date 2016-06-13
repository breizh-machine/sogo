<?php

namespace Scubs\CoreDomain\Game;

class GameLogicException extends \Exception
{
    public static $NO_VISITOR_JOINED = 8000;
    public static $UNPLAYABLE_POSITION = 8001;
    public static $ALREADY_PLAYED_POSITION = 8002;
    public static $GAME_ENDED = 8003;
    public static $NOT_PLAYER_TURN = 8004;
}