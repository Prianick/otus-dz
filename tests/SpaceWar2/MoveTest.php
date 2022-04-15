<?php

namespace OtusDZ\Tests\SpaceWar2;

use OtusDZ\Src\SpaceWar2\Data\Vector;
use OtusDZ\Src\SpaceWar2\Moves\Movable;
use OtusDZ\Src\SpaceWar2\Moves\Move;
use OtusDZ\Src\SpaceWar2\Moves\Rotatable;
use OtusDZ\Src\SpaceWar2\Moves\Rotate;
use PHPUnit\Framework\TestCase;
use Exception;

class MoveTest extends TestCase
{
    public function testMove()
    {
        $movableObject = $this->createMock(Movable::class);
        $movableObject->method('getPosition')
            ->willReturn(new Vector(12, 5));
        $movableObject->method('getVelocity')
            ->willReturn(new Vector(-7, 3));
        $movableObject->method('setPosition')
            ->with($this->equalTo(new Vector(5, 8)));

        $move = new Move();
        $move->move($movableObject)->execute();

        $this->assertEquals(1, 1);
    }

    public function testGetPositionWithException()
    {
        $movableObject = $this->createMock(Movable::class);
        $movableObject->method('getPosition')
            ->will($this->throwException(new Exception()));

        $this->expectException(Exception::class);
        $move = new Move();
        $move->move($movableObject)->execute();
    }

    public function testGetVelocityWithException()
    {
        $movableObject = $this->createMock(Movable::class);
        $movableObject->method('getVelocity')
            ->will($this->throwException(new Exception()));

        $this->expectException(Exception::class);
        $move = new Move();
        $move->move($movableObject)->execute();
    }

    public function testSetPositionWithException()
    {
        $movableObject = $this->createMock(Movable::class);
        $movableObject->method('setPosition')
            ->will($this->throwException(new Exception()));

        $this->expectException(Exception::class);
        $move = new Move();
        $move->move($movableObject)->execute();
    }

    public function testRotate()
    {
        $rotatableObject = $this->createMock(Rotatable::class);
        $rotatableObject->method('getDirection')
            ->willReturn(15);
        $rotatableObject->method('getDirectionsNumber')
            ->willReturn(8);
        $rotatableObject->method('getSpeedValue')
            ->willReturn(1);
        $rotatableObject->method('setVelocity')
            ->with($this->equalTo(new Vector(1, 0)));

        $rotate = new Rotate();
        $rotate->rotate($rotatableObject)->execute();
        $this->assertEquals(1, 1);
    }
}
