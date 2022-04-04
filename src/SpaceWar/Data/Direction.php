<?php

namespace OtusDZ\Src\SpaceWar\Data;

class Direction
{
    public int $direction = 0;

    public int $directionNumber = 8;

    public function __construct(float $direction)
    {
        $this->direction = $direction;
    }

    public function nextP(int $d): self
    {
        $this->direction = ($this->direction + $d) % $this->directionNumber;

        return $this;
    }
}
