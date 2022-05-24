<?php

namespace OtusDZ\Src\Dz4MacroCommand\Adapters;

use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;
use OtusDZ\Src\Dz4MacroCommand\Objects\FuelReducible;

class FuelReducibleAdapter implements FuelReducible
{
    public UObject $object;

    public function __construct(UObject $o)
    {
        $this->object = $o;
    }

    public function setFuelLevel(int $fuelLevel)
    {
        return $this->object->setProperty('FuelLevel', $fuelLevel);
    }

    public function getFuelLevel(): int
    {
        return $this->object->getProperty('FuelLevel');
    }

    public function getFuelConsumption(): int
    {
        return $this->object->getProperty('FuelConsumption');
    }
}
