<?php

namespace OtusDZ\Src\SpaceWar\Moves;

class Rotate
{
    public Rotatable $r;

    public function rotate(Rotatable $r): self
    {
        $this->r = $r;

        return $this;
    }

    public function execute(): void
    {
        $this->r->setDirection(
            $this->r->getDirection()->nextP($this->r->getAngularVelocity())
        );
    }
}
