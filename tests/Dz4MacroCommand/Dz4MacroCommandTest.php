<?php

namespace Dz4MacroCommand;

use OtusDZ\Src\Dz2MoveAndRotate\Data\Vector;
use OtusDZ\Src\Dz2MoveAndRotate\Moves\Rotatable;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\Commands\BurnFuelCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\ChangeVelocityCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\CheckFuelCommand;
use OtusDZ\Src\Dz4MacroCommand\CommandException;
use OtusDZ\Src\Dz4MacroCommand\Commands\MacroCommand;
use OtusDZ\Src\Dz4MacroCommand\MacroCommandsProvider;
use OtusDZ\Src\Dz4MacroCommand\Objects\FuelReducible;
use PHPUnit\Framework\TestCase;

class Dz4MacroCommandTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        MacroCommandsProvider::initMacroCommand();
    }

    public function testBurnFuelCommand()
    {
        $object = $this->createMock(FuelReducible::class);
        $object->method('getFuelLevel')->willReturn(3);
        $object->method('getFuelConsumption')->willReturn(1);
        $object->method('setFuelLevel')->with($this->equalTo(2));
        $cmd = new BurnFuelCommand($object);
        $cmd->execute();
        $this->assertEquals(1, 1);
    }

    public function testCheckFuelCommand()
    {
        $object = $this->createMock(FuelReducible::class);
        $object->method('getFuelLevel')->willReturn(0);
        $this->expectException(CommandException::class);
        $cmd = new CheckFuelCommand($object);
        $cmd->execute();
    }

    public function testMacroCommand()
    {
        $moveCmd = $this->createMock(Command::class);
        $checkFuelCmd = $this->createMock(Command::class);
        $this->expectException(CommandException::class);
        $checkFuelCmd->method('execute')
            ->will($this->throwException(new CommandException()));
        $burnFuelCmd = $this->createMock(Command::class);
        $burnFuelCmd->expects($this->never())->method('execute');
        $commands = [
            $moveCmd,
            $checkFuelCmd,
            $burnFuelCmd,
        ];
        $macroCommand = new MacroCommand();
        $macroCommand->setCommands($commands);
        $macroCommand->execute();
    }

    public function testChangeVelocity()
    {
        $rotatable = $this->createMock(Rotatable::class);
        $rotatable->method('getDirection')
            ->willReturn(1);
        $rotatable->method('getDirectionsNumber')
            ->willReturn(8);
        $rotatable->method('getSpeedValue')
            ->willReturn(1);
        $rotatable->method('setVelocity')
            ->with($this->equalTo(new Vector(0.0, 1.0)));

        $rotate = new ChangeVelocityCommand($rotatable);
        $rotate->execute();
        $this->assertEquals(1, 1);
    }
}
