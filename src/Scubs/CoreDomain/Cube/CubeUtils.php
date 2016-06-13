<?php

namespace Scubs\CoreDomain\Cube;

class CubeUtils
{

    public static $RARITY_PROBABILITIES = [0.9999999, 0.999, 0.9, 0.75, 0.5];

    public static function pickRandomRarity()
    {
        $randomNumber = rand(0, 10000000);
        $floatRandomNumber = $randomNumber / 10000000;
        foreach (self::$RARITY_PROBABILITIES as $ind => $probability) {
            if ($floatRandomNumber >= $probability) {
                return count(self::$RARITY_PROBABILITIES) - $ind;
            }
        }
        return 0;
    }
    
}