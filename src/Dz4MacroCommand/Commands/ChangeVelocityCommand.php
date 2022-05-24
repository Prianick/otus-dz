<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use OtusDZ\Src\Dz1ModuleTest\CustomFloat;
use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;
use OtusDZ\Src\Dz2MoveAndRotate\Moves\Rotatable;

class ChangeVelocityCommand implements Command
{
    public Rotatable $o;

    public function __construct(Rotatable $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        $direction = $this->o->getDirection();
        $directionNumber = $this->o->getDirectionsNumber();
        $direction = ($direction + 1) % $directionNumber;
        $this->o->setDirection($direction);

        // Если объект неподвижный(пушка), то скорость будет ноль, и вектор будет {0,0}
        $speed = $this->o->getSpeedValue();
        $x = $speed * CustomFloat::cos(deg2rad(360 / $directionNumber * $direction));
        $y = $speed * CustomFloat::sin(deg2rad(360 / $directionNumber * $direction));
        $this->o->setVelocity(new Vector($x, $y));
    }
}
