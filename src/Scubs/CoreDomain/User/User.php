<?php

namespace Scubs\CoreDomain\User;

use FOS\UserBundle\Model\User as BaseUser;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Player\ScubPlayer;

class User extends BaseUser implements ScubPlayer
{
    protected $credits;
    public static $INITIAL_CREDITS = 100;

    public function __construct(ResourceId $playerId, $credits = null)
    {
        parent::__construct();
        $this->id = $playerId->getValue();
        $this->credits = $credits ? $credits : self::$INITIAL_CREDITS;
    }

    /**
     * @return int|null
     */
    public function getCredits()
    {
        return $this->credits;
    }

    public function getId()
    {
        return new ResourceId($this->id);
    }

    public function equals(ScubPlayer $player)
    {
        return $this->id == $player->getId()->getValue();
    }
}