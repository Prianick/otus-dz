<?php

namespace OtusDZ\Src\SpaceWar\Moves;

use OtusDZ\Src\SpaceWar\Data\Vector;

interface Movable
{
    public function getPosition(): Vector;

    public function getVelocity(): float;

    public function setPosition(Vector $newV);
}
