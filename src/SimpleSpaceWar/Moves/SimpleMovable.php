<?php

namespace OtusDZ\Src\SimpleSpaceWar\Moves;

use OtusDZ\Src\SimpleSpaceWar\Data\SimpleVector;

interface SimpleMovable
{
    public function getPosition(): SimpleVector;

    public function getVelocity(): SimpleVector;

    public function setPosition(SimpleVector $v);
}

