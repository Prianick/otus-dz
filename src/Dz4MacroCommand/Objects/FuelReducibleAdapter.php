<?php

namespace OtusDZ\Src\Dz4MacroCommand\Objects;

use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;

class FuelReducibleAdapter implements FuelReducible
{
    public UObject $o;

    public function __construct(UObject $o)
    {
        $this->o = $o;
    }

    public function getFuelLevel(): int
    {
        return $this->o->getProperty('fuelLevel');
    }

    public function setFuelLevel(int $fuelLevel): void
    {
        $this->o->setProperty('fuelLevel', $fuelLevel);
    }

    public function getFuelConsumption(): int
    {
        return $this->o->getProperty('fuelConsumption');
    }
}
