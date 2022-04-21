<?php

namespace OtusDZ\Src\Dz2MoveAndRotate\Moves;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;

interface Movable
{
    public function getPosition(): Vector;

    public function getVelocity(): Vector;

    public function setPosition(Vector $newV);
}
