<?php

namespace Scubs\CoreDomain\User;

use FOS\UserBundle\Model\User as BaseUser;
use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\Player\ScubPlayer;

class User extends BaseUser implements ScubPlayer
{
    protected $credits;
    protected $profilePicture;
    public static $INITIAL_CREDITS = 100;
    private static $DEFAULT_PROFILE_PICTURE = 'default_profile.png';

    public function __construct(ResourceId $playerId = null, $credits = null)
    {
        parent::__construct();
        if ($playerId == null) {
            $playerId = new ResourceId();
        }
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

    public function wonBet($bet)
    {
        $this->credits += $bet * 2;
        return $this;
    }
    
    public function bet($bet)
    {
        if ($this->credits - $bet < 0)
        {
            throw new GameLogicException(GameLogicException::$INSUFFICIENT_CREDITS_TO_BET_MESS, GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
        }
        $this->credits -= $bet;
        return $this;
    }

    public function refund($bet)
    {
        $this->credits += $bet;
        return $this;
    }

    public function equals(ScubPlayer $player)
    {
        return $this->id == $player->getId()->getValue();
    }

    /**
     * @return mixed
     */
    public function getProfilePicture()
    {
        //TODO
        return $this->profilePicture !== null ? $this->profilePicture : self::$DEFAULT_PROFILE_PICTURE;
    }

    /**
     * @return string
     */
    public static function getDefaultProfilePicture()
    {
        return self::$DEFAULT_PROFILE_PICTURE;
    }



}