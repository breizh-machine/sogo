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
    public static $SAME_CUBES_IN_GAME = 8011;
    public static $SAME_CUBES_IN_GAME_MESS = 'Visitor and local must have different cubes in game';
    public static $NO_LOCAL = 8012;
    public static $NO_LOCAL_MESS = 'No local player defined';
    public static $LOCAL_CUBE_NOT_OWNED = 8013;
    public static $LOCAL_CUBE_NOT_OWNED_MESS = 'Local does not have the cube he wants to play with';
    public static $LOCAL_AND_VISITOR_ALREADY_PLAYING = 8014;
    public static $LOCAL_AND_VISITOR_ALREADY_PLAYING_MESS = 'Local and visitor cannot have more than one game running';
    public static $VISITOR_NOT_FOUND = 8015;
    public static $VISITOR_NOT_FOUND_MESS = 'Cannot find visitor';
    public static $BET_IS_NULL_OR_NEGATIVE = 8016;
    public static $BET_IS_NULL_OR_NEGATIVE_MESS = 'Bet must be superior to zero';



}