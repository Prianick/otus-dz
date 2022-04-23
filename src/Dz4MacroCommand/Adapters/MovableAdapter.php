<?php

namespace OtusDZ\Src\Dz4MacroCommand\Adapters;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;
use OtusDZ\Src\Dz2MoveAndRotate\Moves\Movable;
use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;

class MovableAdapter implements Movable
{
    public UObject $object;

    public function __construct(UObject $o)
    {
        $this->object = $o;
    }

    public function getPosition(): Vector
    {
        return $this->object->getProperty('position');
    }

    public function getVelocity(): Vector
    {
        return $this->object->getProperty('velocity');
    }

    public function setPosition(Vector $newV)
    {
        $this->object->setProperty('position', $newV);
    }
}
