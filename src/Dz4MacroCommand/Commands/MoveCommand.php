<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use OtusDZ\Src\Dz2MoveAndRotate\Moves\Movable;

class MoveCommand implements Command
{
    public Movable $o;

    public function __construct(Movable $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        $position = $this->o->getPosition();
        $velocity = $this->o->getVelocity();
        $this->o->setPosition($position->plus($velocity));
    }
}
