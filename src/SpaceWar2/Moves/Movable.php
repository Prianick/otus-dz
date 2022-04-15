<?php

namespace OtusDZ\Src\SpaceWar2\Moves;

use OtusDZ\Src\SpaceWar2\Data\Vector;

interface Movable
{
    public function getPosition(): Vector;

    public function getVelocity(): Vector;

    public function setPosition(Vector $newV);
}
