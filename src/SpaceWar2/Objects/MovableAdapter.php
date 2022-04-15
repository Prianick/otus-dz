<?php

namespace OtusDZ\Src\SpaceWar2\Objects;

use OtusDZ\Src\Dz1ModuleTest\CustomFloat;
use OtusDZ\Src\SpaceWar2\Data\Vector;
use OtusDZ\Src\SpaceWar2\Moves\Movable;

class MovableAdapter implements Movable
{
    public UObject $o;

    public function __construct(UObject $o)
    {
        $this->o = $o;
    }

    public function getPosition(): Vector
    {
        return $this->o->getProperty('position');
    }

    public function setPosition(Vector $newV)
    {
        return $this->o->setProperty('position', $newV);
    }

    public function getVelocity(): Vector
    {
        return $this->o->getProperty('velocity');
    }

    public function getAngularVelocity(): Vector
    {
        $direction = $this->o->getProperty('Direction');
        $directionNumber = $this->o->getProperty('DirectionsNumber');
        $velocity = $this->o->getProperty('Velocity');

        $x = $velocity * CustomFloat::cos(deg2rad(360 / $directionNumber * $direction));
        $y = $velocity * CustomFloat::sin(deg2rad(360 / $directionNumber * $direction));

        return new Vector($x, $y);
    }
}
