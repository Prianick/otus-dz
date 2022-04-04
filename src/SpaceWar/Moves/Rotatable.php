<?php

namespace OtusDZ\Src\SpaceWar\Moves;

use OtusDZ\Src\SpaceWar\Data\Direction;

interface Rotatable
{
    public function getDirection(): Direction;

    public function getAngularVelocity(): float;

    public function setDirection(Direction $direction): void;
}
