<?php

namespace Scubs\ApiBundle\PushMessage;

use Scubs\ApiBundle\View\GameListItemView;
use Scubs\PushBundle\Message\PushMessage;

class VisitorJoinedPushMessage extends PushMessage
{
    public static $VISITOR_JOINED_TYPE = 'VISITOR_JOINED_TYPE';

    public function __construct(GameListItemView $gameView, $localId)
    {
        $channel = sprintf('%s%s', Channels::$GAME_CHANNEL, $localId);
        parent::__construct($channel, $gameView->toArray(), self::$VISITOR_JOINED_TYPE);
    }
}