<?php

namespace Scubs\CoreDomain\Game;

use Scubs\CoreDomain\Turn\Turn;
use Doctrine\Common\Collections\ArrayCollection;

class GameUtils
{
    public static function isWinningDirection(ArrayCollection $turns, $gridSize, Turn $turn, ArrayCollection $line)
    {
        $turns = self::getWinningTurnsFromLine($turns, $gridSize, $turn, $line);
        return $turns->count() === $gridSize;
    }

    public static function getWinningTurnsFromLine(ArrayCollection $turns, $gridSize, Turn $turn, ArrayCollection $line)
    {
        $winningTurns = new ArrayCollection();
        foreach ($line as $position) {
            $currentTurn = self::getTurnAtPosition($turns, $gridSize, $position[0], $position[1], $position[2]);
            if ($currentTurn !== null && $currentTurn->getPlayer()->equals($turn->getPlayer())) {
                $winningTurns->add($currentTurn);
            }
        }
        return $winningTurns;
    }

    public static function getLastTurn(ArrayCollection $turns)
    {
        $maxTimeStamp = null;
        $index = 0;
        foreach ($turns as $key => $turn)
        {
            $currentTimestamp = $turn->getStartDate()->getTimestamp();
            if ($maxTimeStamp === null) {
                $maxTimeStamp = $currentTimestamp;
            } else if ($currentTimestamp >= $maxTimeStamp) {
                $maxTimeStamp = $currentTimestamp;
                $index = $key;
            }
        }
        return $turns->get($index);
    }
    
    public static function getPlayablePositions($gridSize, Turn $turn, Turn $neighbor) {
        $forwardVector = [$turn->getX() - $neighbor->getX(), $turn->getY() - $neighbor->getY(), $turn->getZ() - $neighbor->getZ()];

        $playablePositions = new ArrayCollection();
        for ($i = -1 * $gridSize; $i <= $gridSize; $i++) {
            $calculatedPosition = [$turn->getX() + ($forwardVector[0] * $i),  $turn->getY() + ($forwardVector[1] * $i), $turn->getZ() + ($forwardVector[2] * $i)];
            if (!self::isOutOfBoundPosition($gridSize, $calculatedPosition[0], $calculatedPosition[1], $calculatedPosition[2])) {
                $playablePositions->add($calculatedPosition);
            }
        }
        return $playablePositions;
    }

    public static function getTurnNeighborhood(ArrayCollection $turns, $gridSize, Turn $turn)
    {
        $neighborhood = new ArrayCollection();
        $xStart = $turn->getX();
        $yStart = $turn->getY();
        $zStart = $turn->getZ();
        for ($indX = $xStart - 1; $indX <= $xStart + 1; $indX++) {
            for ($indY = $yStart - 1; $indY <= $yStart + 1; $indY++) {
                for ($indZ = $zStart - 1; $indZ <= $zStart + 1; $indZ++) {
                    if (!self::isOutOfBoundPosition($gridSize, $indX, $indY, $indZ)) {
                        $neighbor = self::getTurnAtPosition($turns, $gridSize, $indX, $indY, $indZ);
                        if ($neighbor !== null && !$neighbor->equals($turn) && $neighbor->getPlayer()->equals($turn->getPlayer())) {
                            $neighborhood->add($neighbor);
                        }
                    }
                }
            }
        }
        return $neighborhood;
    }

    public static function isOutOfBoundPosition($gridSize, $x, $y, $z)
    {
        return ($x < 0 || $x >= $gridSize ||
            $y < 0 || $y >= $gridSize ||
            $z < 0 || $z >= $gridSize);
    }

    public static function getTurnAtPosition($turns, $gridSize, $x, $y, $z)
    {
        if ($y < 0 || $y >= $gridSize) {
            return null;
        }
        $turnsAtXzPosition = self::getTurnsAtPosition($turns, $gridSize, $x, $z);
        return $turnsAtXzPosition->get($y);
    }

    public static function getTurnsAtPosition($turns, $gridSize, $x, $z)
    {
        $positions = new ArrayCollection(array_fill(0, $gridSize, null));
        foreach ($turns as  $turn)
        {
            if ($turn->getX() == $x && $turn->getZ() == $z)
            {
                $positions->set($turn->getY(), $turn);
            }
        }
        return $positions;
    }




}
