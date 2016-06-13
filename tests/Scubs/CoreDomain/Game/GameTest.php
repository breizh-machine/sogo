<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Game\Game;
use Scubs\CoreDomain\Game\GameId;
use Scubs\CoreDomain\Player\Player;
use Scubs\CoreDomain\Player\PlayerId;
use Scubs\CoreDomain\Game\GameUtils;

class GameTest extends \PHPUnit_Framework_TestCase
{
    public function testGame()
    {
        $local = new Player(new PlayerId('LOCAL'));
        $visitor = new Player(new PlayerId('VISITOR'));
        $game = new Game(new GameId(), $local);

        $game->inviteVisitor($visitor);

        $game->play($local, 0, 0, 0);
        $game->play($visitor, 1, 0, 0);

        $game->play($local, 1, 1, 0);
        $game->play($visitor, 2, 0, 0);
        
        $turns = $game->getTurns();
        dump(GameUtils::getPlayablePositions(4, $turns->get(0), $turns->get(2)));

        $game->play($local, 3, 0, 0);
        $game->play($visitor, 2, 1, 0);
        $this->assertFalse($game->isGameWon());

        $game->play($local, 2, 2, 0);
        $game->play($visitor, 3, 1, 0);

        $game->play($local, 3, 2, 0);
        $game->play($visitor, 0, 1, 0);

        $game->play($local, 3, 3, 0);

        $this->assertTrue($game->isGameWon());
        //dump($game->getWinningTurns());

        //$game->play($visitor, 3, 3, 3);



    }
}