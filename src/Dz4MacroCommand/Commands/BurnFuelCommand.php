<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\Objects\FuelReducible;

class BurnFuelCommand implements Command
{
    protected FuelReducible $o;

    public function __construct(FuelReducible $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        $this->o->setFuelLevel($this->o->getFuelLevel() - $this->o->getFuelConsumption());
    }
}
