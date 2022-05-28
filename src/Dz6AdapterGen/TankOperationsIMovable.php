<?php

namespace OtusDZ\Src\Dz6AdapterGen;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;

interface TankOperationsIMovable
{
    public function getPosition(): Vector;

    public function setPosition(Vector $newValue);

    public function getVelocity(): Vector;
}
