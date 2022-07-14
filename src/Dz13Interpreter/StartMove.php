<?php

namespace OtusDZ\Src\Dz13Interpreter;

use OtusDZ\Src\Dz2MoveAndRotate\Moves\Movable;
use OtusDZ\Src\Dz4MacroCommand\Commands\Command;

class StartMove implements Command
{
    public Movable $obj;

    public function __construct(Movable $o)
    {
        $this->obj = $o;
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
