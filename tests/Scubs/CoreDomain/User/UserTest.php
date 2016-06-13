<?php

namespace Test\Scubs\CoreDomain\User;

use Scubs\CoreDomain\Core\ResourceId;
use Scubs\CoreDomain\Game\GameLogicException;
use Scubs\CoreDomain\User\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testBet()
    {
        $user = new User(new ResourceId(), 100);
        try {
            $user->bet(1000);
        } catch (GameLogicException $e) {
            $this->assertTrue($e->getCode() === GameLogicException::$INSUFFICIENT_CREDITS_TO_BET);
        }
        $user->bet(25);
        $this->assertTrue($user->getCredits() == 75);
    }
}