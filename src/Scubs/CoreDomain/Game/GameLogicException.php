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
    public static $GAME_NOT_ENDED_FOR_REWARD = 8007;
    public static $GAME_NOT_ENDED_FOR_REWARD_MESS = 'Game must be ended to have a reward';
    public static $REWARD_ALREADY_ASSIGNED = 8008;
    public static $REWARD_ALREADY_ASSIGNED_MESS = 'Reward already assigned to this game';
    public static $REWARD_ALREADY_ASSIGNED_TO_GAME = 8009;
    public static $REWARD_ALREADY_ASSIGNED_TO_GAME_MESS = 'Reward already assigned to this game';
    public static $INSUFFICIENT_CREDITS_TO_BET = 8010;
    public static $INSUFFICIENT_CREDITS_TO_BET_MESS = 'Player does not have enough credit to bet';

}