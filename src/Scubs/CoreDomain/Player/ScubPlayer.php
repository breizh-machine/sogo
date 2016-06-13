<?php

namespace Scubs\CoreDomain\Player;

interface ScubPlayer
{
    public function getId();
    public function getCredits();
    public function equals(ScubPlayer $player);
    public function wonBet($bet);
    public function bet($bet);
}