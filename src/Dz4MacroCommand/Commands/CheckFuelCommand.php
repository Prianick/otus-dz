<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use Exception;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\CommandException;
use OtusDZ\Src\Dz4MacroCommand\Objects\FuelReducible;

class CheckFuelCommand implements Command
{
    public FuelReducible $o;

    public function __construct(FuelReducible $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        if ($this->o->getFuelLevel() <= 0) {
            throw new Exception('Not enough fuel.');
        }
    }
}
