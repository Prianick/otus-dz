<?php

namespace OtusDZ\Src\SpaceWar\Objects;

use OtusDZ\Src\SpaceWar\Data\Direction;
use OtusDZ\Src\SpaceWar\Data\Vector;
use OtusDZ\Src\SpaceWar\Moves\Movable;
use OtusDZ\Src\SpaceWar\Moves\Rotatable;

class SpaceShip implements Movable, Rotatable
{
    public Vector $position;

    public float $velocity;

    public Direction $direction;

    public float $angularVelocity = 1;

    public function getPosition(): Vector
    {
        return $this->position;
    }

    public function getVelocity(): float
    {
        return $this->velocity;
    }

    public function setPosition(Vector $newV): void
    {
        $this->position = $newV;
    }

    public function setDirection(Direction $direction): void
    {
        $this->direction = $direction;
    }

    public function getDirection(): Direction
    {
        return $this->direction;
    }

    public function getAngularVelocity(): float
    {
        return $this->angularVelocity;
    }
}
