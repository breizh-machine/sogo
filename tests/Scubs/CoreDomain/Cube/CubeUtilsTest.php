<?php

namespace Test\Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Cube\CubeUtils;

class CubeUtilsTest extends \PHPUnit_Framework_TestCase
{
    private $nbRepetitions = 100000;

    public function testPickRandomRarity()
    {
        $stats = [0, 0, 0, 0, 0];
        for ($i = 0; $i < $this->nbRepetitions; $i++)
        {
            $rarity = CubeUtils::pickRandomRarity();
            $stats[$rarity]++;
        }
        foreach ($stats as $ind => $stat) {
            $stats[$ind] = $stat / $this->nbRepetitions;
        }
        var_dump($stats);
    }

}