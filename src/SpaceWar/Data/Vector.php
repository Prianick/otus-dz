<?php

namespace OtusDZ\Src\SpaceWar\Data;

use OtusDZ\Src\Dz1ModuleTest\CustomFloat;

class Vector
{
    public float $x = 0;

    public float $y = 0;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function plus(float $velocity, int $direction, int $directionNumber): Vector
    {
        $this->x = $this->x + $velocity * CustomFloat::cos(deg2rad(360 / $directionNumber * $direction));
        $this->y = $this->y + $velocity * CustomFloat::sin(deg2rad(360 / $directionNumber * $direction));

        return $this;
    }
}
