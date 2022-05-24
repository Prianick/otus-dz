<?php

namespace OtusDZ\Src\Dz4MacroCommand\Objects;

interface FuelReducible
{
    public function setFuelLevel(int $fuelLevel);

    public function getFuelLevel(): int;

    public function getFuelConsumption(): int;
}
