<?php

namespace OtusDZ\Tests\SpaceWar;

use OtusDZ\Src\SimpleSpaceWar\Data\SimpleVector;
use OtusDZ\Src\SimpleSpaceWar\Errors\Errors;
use OtusDZ\Src\SimpleSpaceWar\Moves\SimpleMove;
use OtusDZ\Src\SimpleSpaceWar\Objects\SimpleSpaceShip;
use OtusDZ\Src\SpaceWar\Moves\Move;
use OtusDZ\Src\SpaceWar\Data\Direction;
use OtusDZ\Src\SpaceWar\Moves\Rotate;
use OtusDZ\Src\SpaceWar\Objects\SpaceShip;
use OtusDZ\Src\SpaceWar\Data\Vector;
use PHPUnit\Framework\TestCase;
use Exception;

class MoveTest extends TestCase
{
    public function testSimpleMove()
    {
        $spaceShip = new SimpleSpaceShip();
        $spaceShip->velocity = new SimpleVector(-7, 3);
        $spaceShip->setPosition(new SimpleVector(12, 5));

        $move = new SimpleMove();
        $move->move($spaceShip)->execute();
        $position = $spaceShip->getPosition();

        $this->assertEquals(5, $position->x, 'wrong x');
        $this->assertEquals(8, $position->y, 'wrong y');

        $spaceShip = new SimpleSpaceShip();
        $move = new SimpleMove();
        try {
            $move->move($spaceShip)->execute();
        } catch (Exception $e) {
            $this->assertEquals(Errors::POSITION_UNSET, $e->getCode());
        }
        $spaceShip->setPosition(new SimpleVector(1, 1));
        try {
            $move->move($spaceShip)->execute();
        } catch (Exception $e) {
            $this->assertEquals(Errors::VELOCITY_UNSET, $e->getCode());
        }
    }

    public function testMove()
    {
        $spaceShip = new SpaceShip();
        $spaceShip->setDirection(new Direction(0));
        $spaceShip->setPosition(new Vector(0, 0));
        $spaceShip->velocity = 2;

        $move = new Move();
        $rotate = new Rotate();

        for ($i = 0; $i < 8; $i++) {
            // Идем в право со скоростью два.
            $move->move($spaceShip)->execute();
            // Поворачиваем на 1 значение (45градусов)
            $rotate->rotate($spaceShip)->execute();
        }

        $this->assertEquals((new Vector(0, 0)), $spaceShip->getPosition());
    }

    public function testRotate()
    {
        $spaceShip = new SpaceShip();
        $spaceShip->setDirection(new Direction(0));
        $rotate = new Rotate();

        for ($i = 0; $i < 8; $i++) {
            $rotate->rotate($spaceShip)->execute();
        }

        $direction = $spaceShip->getDirection();
        $this->assertEquals(0, $direction->direction);
    }
}
