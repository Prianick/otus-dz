<?php

namespace Dz12ChainOfResponsibility;

use OtusDZ\Src\Dz12ChainOfResponsibility\AbstractField;
use OtusDZ\Src\Dz12ChainOfResponsibility\CheckCollisionCommand;
use OtusDZ\Src\Dz12ChainOfResponsibility\Field;
use OtusDZ\Src\Dz12ChainOfResponsibility\FieldSet;
use OtusDZ\Src\Dz12ChainOfResponsibility\PositionDetectable;
use OtusDZ\Src\Dz12ChainOfResponsibility\ProcessCollisionCommand;
use OtusDZ\Src\Dz12ChainOfResponsibility\SetCurrentFieldCommand;
use OtusDZ\Src\Dz12ChainOfResponsibility\UObject;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use OtusDZ\Src\Dz4MacroCommand\Commands\MacroCommand;
use OtusDZ\Src\Dz5IoC\IoC;
use OtusDZ\Src\Dz5IoC\IoCRegister;
use OtusDZ\Src\Dz6AdapterGen\AdapterCreator;
use PHPUnit\Framework\TestCase;

class TestField extends TestCase
{
    protected QueueFunction $queue;

    protected function setUp(): void
    {
        IoC::resolve(IoC::IOC_REGISTER, IoC::IOC_REGISTER, function () {
            return new IoCRegister();
        })->execute();

        $mainFieldSet = new FieldSet(0, 0);
        $mainFieldSet->setChild(900);
        IoC::resolve(IoC::IOC_REGISTER, AbstractField::MAIN_FIELD_SET, function () use ($mainFieldSet) {
            return $mainFieldSet;
        })->execute();

        $secondFieldSet = new FieldSet(-50, -50);
        $secondFieldSet->setChild(900);
        IoC::resolve(IoC::IOC_REGISTER, AbstractField::SECOND_FIELD_SET, function () use ($secondFieldSet) {
            return $secondFieldSet;
        })->execute();

        IoC::resolve(IoC::IOC_REGISTER, PositionDetectable::class, function ($x, $y, $id) {
            /** @var PositionDetectable $object */
            $object = AdapterCreator::create(PositionDetectable::class, new UObject());
            $object->setX($x);
            $object->setY($y);
            $object->setId($id);
            $object->setCurrentField(new Field(0, 0));
            $object->setCurrentSecondaryField(new Field(0, 0));

            return $object;
        })->execute();

        $this->queue = $this->createMock(QueueFunction::class);
        IoC::resolve(IoC::IOC_REGISTER, 'Queue', function () {
            return $this->queue;
        })->execute();
    }

    public function testFindCurrentField()
    {
        $fieldSet = new FieldSet(0, 0);
        $fieldSet->setChild(900);

        $object1Position = [333, 444];

        $field = $fieldSet->getCurrentField($object1Position[0], $object1Position[1]);

        $this->assertEquals(300, $field->x);
        $this->assertEquals(400, $field->y);
    }

    public function testFindCurrentFieldInSecondary()
    {
        $fieldSet = new FieldSet(-50, -50);
        $fieldSet->setChild(900);

        $object1Position = [333, 444];

        $field = $fieldSet->getCurrentField($object1Position[0], $object1Position[1]);

        $this->assertEquals(250, $field->x);
        $this->assertEquals(350, $field->y);
    }

    public function testMacroCommand()
    {
        $idOfCollisionObj1OnMainFieldSet = 1;
        $idOfCollisionObj2OnMainFieldSet = 2;
        $idOfCollisionObj1OnSecondFieldSet = 5;
        $idOfCollisionObj2OnSecondFieldSet = 6;

        $objectCoordinates = [
            [122, 401, $idOfCollisionObj1OnMainFieldSet],
            [140, 451, $idOfCollisionObj2OnMainFieldSet],
            [522, 601, 3],
            [601, 801, 4],
            [797, 803, $idOfCollisionObj1OnSecondFieldSet],
            [801, 803, $idOfCollisionObj2OnSecondFieldSet],
        ];

        $macroCommand = new MacroCommand();
        $commands = [];
        $objects = [];
        foreach ($objectCoordinates as $objectCoordinate) {
            /** @var PositionDetectable $object */
            $object = IoC::resolve(PositionDetectable::class, $objectCoordinate);
            $objects[] = $object;
            $commands[] = new SetCurrentFieldCommand($object);
        }

        foreach ($objects as $object) {
            /** @var PositionDetectable $object */
            $commands[] = new CheckCollisionCommand($object);
        }
        $this->queue->expects($this->atLeastOnce())->method('push')->with(
            $this->callback(
                function (ProcessCollisionCommand $command) use (
                    $idOfCollisionObj1OnMainFieldSet,
                    $idOfCollisionObj1OnSecondFieldSet
                ): bool {
                    if ($command->where == AbstractField::MAIN_FIELD_SET) {
                        $this->assertTrue(
                            $command->o1->getId() == $idOfCollisionObj1OnMainFieldSet
                            || $command->o2->getId() == $idOfCollisionObj1OnMainFieldSet
                        );
                    }

                    if ($command->where == AbstractField::SECOND_FIELD_SET) {
                        $this->assertTrue(
                            $command->o1->getId() == $idOfCollisionObj1OnSecondFieldSet
                            || $command->o2->getId() == $idOfCollisionObj1OnSecondFieldSet
                        );
                    }

                    return true;
                }
            )
        );
        $macroCommand->setCommands($commands);
        $macroCommand->execute();
    }
}
