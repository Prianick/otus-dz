<?php

namespace Dz6Adapter;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;
use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz6AdapterGen\AdapterCreator;
use OtusDZ\Src\Dz6AdapterGen\TankOperationsIMovable;
use PHPUnit\Framework\TestCase;

class Dz6AdapterTest extends TestCase
{
    /**
     *
     */
    public function setUp(): void
    {

        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, function () {
            return new IoCRegister();
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, 'Adapter', function ($interfaceName, $uObject) {
            return AdapterCreator::create(TankOperationsIMovable::class, $uObject);
        })->execute();
        IoC::resolve(IoC::IOC_REGISTER, 'Tank.Operations.IMovable:position.get', function ($object) {
            return $object->getProperty('position');
        })->execute();
        IoC::resolve(
            IoC::IOC_REGISTER,
            'Tank.Operations.IMovable:position.set',
            function ($object, Vector $vector) {
                return $object->setProperty('position', $vector);
            }
        )->execute();
        IoC::resolve(IoC::IOC_REGISTER, 'Tank.Operations.IMovable:velocity.get', function ($object) {
            return $object->getProperty('velocity');
        })->execute();
        parent::setUp();
    }

    /**
     *
     */
    public function testAdapter()
    {
        $testVector = new Vector(10, 10);
        $uObjectMock = $this->createMock(UObject::class);
        $uObjectMock->method('getProperty')->willReturn($testVector);
        $uObjectMock->method('setProperty')->with('position', $testVector);
        $result = IoC::resolve('Adapter', [TankOperationsIMovable::class, $uObjectMock]);
        $result->setPosition($testVector);
        $position = $result->getPosition();
        $this->assertEquals($testVector, $position);
    }
}



