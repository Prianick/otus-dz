<?php

namespace OtusDZ\Src\SpaceWar\Moves;

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
        $this->object->setPosition(
            $this->object->getPosition()->plus(
                $this->object->getVelocity(),
                $this->object->getDirection()->direction,
                $this->object->getDirection()->directionNumber,
            )
        );
    }
}
