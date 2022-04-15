<?php

namespace OtusDZ\Src\SpaceWar2\Moves;

class Move
{
    protected Movable $object;

    public function move(Movable $object): Move
    {
        $this->object = $object;

        return $this;
    }

    public function execute(): void
    {
        $position = $this->object->getPosition();
        $velocity = $this->object->getVelocity();
        $this->object->setPosition($position->plus($velocity));
    }
}
