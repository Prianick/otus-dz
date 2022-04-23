<?php

namespace OtusDZ\Src\Dz2MoveAndRotate\Moves;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;

interface Rotatable
{
    public function getDirection(): int;

    public function getDirectionsNumber(): int;

    public function getSpeedValue(): int;

    public function setDirection(int $direction): void;

    public function setVelocity(Vector $velocity): void;
}
