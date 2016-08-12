<?php

namespace Scubs\ApiBundle\PushMessage;

use Scubs\ApiBundle\View\GameListItemView;
use Scubs\PushBundle\Message\PushMessage;

class GameInvitationPushMessage extends PushMessage
{
    public static $GAME_INVITATION_TYPE = 'GAME_INVITATION_TYPE';

    public function __construct(GameListItemView $gameView, $visitorId)
    {
        $channel = sprintf('%s%s', Channels::$GAME_CHANNEL, $visitorId);
        parent::__construct($channel, $gameView->toArray(), self::$GAME_INVITATION_TYPE);
    }
}