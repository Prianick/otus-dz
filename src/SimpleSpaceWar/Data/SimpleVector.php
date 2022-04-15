<?php

namespace OtusDZ\Src\SimpleSpaceWar\Data;

class SimpleVector
{
    public int $x = 0;

    public int $y = 0;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function plus(SimpleVector $vector): self
    {
        $this->x += $vector->x;
        $this->y += $vector->y;

        return $this;
    }
}
