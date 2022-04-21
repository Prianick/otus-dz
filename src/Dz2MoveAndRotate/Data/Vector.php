<?php

namespace OtusDZ\Src\Dz2MoveAndRotate\Data;

class Vector
{
    public int $x = 0;

    public int $y = 0;

    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function plus(Vector $vector): Vector
    {
        return new Vector($vector->x + $this->x, $vector->y + $this->y);
    }
}
