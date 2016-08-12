<?php

namespace Scubs\ApiBundle\PushMessage;

use Scubs\ApiBundle\View\GameListItemView;
use Scubs\PushBundle\Message\PushMessage;

class GameCreatedPushMessage extends PushMessage
{
    public static $GAME_CREATED_TYPE = 'GAME_CREATED_TYPE';

    public function __construct(GameListItemView $gameView, $localId)
    {
        $channel = sprintf('%s%s', Channels::$GAME_CHANNEL, $localId);
        parent::__construct($channel, $gameView->toArray(), self::$GAME_CREATED_TYPE);
    }
}