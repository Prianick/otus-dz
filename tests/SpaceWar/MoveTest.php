<?php

namespace OtusDZ\Tests\SpaceWar;

use OtusDZ\Src\SimpleSpaceWar\Data\SimpleVector;
use OtusDZ\Src\SimpleSpaceWar\Errors\Errors;
use OtusDZ\Src\SimpleSpaceWar\Moves\SimpleMovable;
use OtusDZ\Src\SimpleSpaceWar\Moves\SimpleMove;
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
        $movableObject = $this->createMock(SimpleMovable::class);
        $movableObject->method('getPosition')
            ->willReturn(new SimpleVector(12, 5));
        $movableObject->method('getVelocity')
            ->willReturn(new SimpleVector(-7, 3));
        $movableObject->method('setPosition')
            ->with($this->equalTo(new SimpleVector(5, 8)));

        $move = new SimpleMove();
        $move->move($movableObject)->execute();

        $movableObject = $this->createMock(SimpleMovable::class);
        $move = new SimpleMove();
        try {
            $move->move($movableObject)->execute();
        } catch (Exception $e) {
            $this->assertEquals(Errors::POSITION_UNSET, $e->getCode());
        }
        try {
            $move->move($movableObject)->execute();
        } catch (Exception $e) {
            $this->assertEquals(Errors::VELOCITY_UNSET, $e->getCode());
        }
    }

    /**
     * Тест показывает, что после восьми поворотов на 45 градусов при равномерном движении мы возвращаемся в начальную
     * точку. Если не поправить ошибку округления конечная точка будет отличаться.
     */
    public function testMoveWithRotate()
    {
        $spaceShip = new SpaceShip();
        $spaceShip->setDirection(new Direction(0));
        $spaceShip->setPosition(new Vector(0, 0));
        $spaceShip->velocity = 2;

        $move = new Move();
        $rotate = new Rotate();

        for ($i = 0; $i < 8; $i++) {
            // Двигаемся со скоростью два.
            $move->move($spaceShip)->execute();
            // Поворачиваем на 1 значение (45градусов)
            $rotate->rotate($spaceShip)->execute();
        }

        $this->assertEquals((new Vector(0.0, 0.0)), $spaceShip->getPosition());
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
