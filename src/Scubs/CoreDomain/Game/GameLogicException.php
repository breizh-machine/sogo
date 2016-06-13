<?php

namespace Scubs\CoreDomain\Game;

class GameLogicException extends \Exception
{
    public static $NO_VISITOR_JOINED = 8000;
    public static $NO_VISITOR_JOINED_MESS = 'There must be a visitor set';
    public static $UNPLAYABLE_POSITION = 8001;
    public static $UNPLAYABLE_POSITION_MESS = 'The position cannot be played';
    public static $ALREADY_PLAYED_POSITION = 8002;
    public static $ALREADY_PLAYED_POSITION_MESS = 'The position is already played';
    public static $GAME_ENDED = 8003;
    public static $GAME_ENDED_MESS = 'The game is over';
    public static $NOT_PLAYER_TURN = 8004;
    public static $NOT_PLAYER_TURN_MESS = 'Wrong player turn';
    public static $GAME_NOT_STARTED = 8005;
    public static $GAME_NOT_STARTED_MESS = 'The game is not started yet';
    public static $SAME_VISITOR_AND_LOCAL = 8006;
    public static $SAME_VISITOR_AND_LOCAL_MESS = 'Same visitor and local in the game';
}