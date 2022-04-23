<?php

namespace OtusDZ\Src\Dz4MacroCommand;

use OtusDZ\Src\Dz2MoveAndRotate\Objects\MovableAdapter;
use OtusDZ\Src\Dz2MoveAndRotate\Objects\UObject;
use OtusDZ\Src\Dz3SolidExceptions\Commands\RepeatedCommand;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz4MacroCommand\Adapters\FuelReducibleAdapter;
use OtusDZ\Src\Dz4MacroCommand\Commands\BurnFuelCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\Commands\LinearMotionMacroCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\CheckFuelCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\MacroCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\MoveCommand;
use OtusDZ\Src\Dz4MacroCommand\Commands\RepeatCommand;

class MacroCommandsProvider
{
    public static function initMacroCommand()
    {
        $setUp = [
            LinearMotionMacroCommand::class => [
                MoveCommand::class,
                CheckFuelCommand::class,
                BurnFuelCommand::class,
            ]
        ];

        IoC::getInstance()->set(MoveCommand::class, static function (UObject $obj) {
            $adapter = new MovableAdapter($obj);
            return new MoveCommand($adapter);
        });
        IoC::getInstance()->set(CheckFuelCommand::class, static function (UObject $obj) {
            $adapter = new FuelReducibleAdapter($obj);
            return new CheckFuelCommand($adapter);
        });
        IoC::getInstance()->set(CheckFuelCommand::class, static function (UObject $obj) {
            $adapter = new FuelReducibleAdapter($obj);
            return new BurnFuelCommand($adapter);
        });
        IoC::getInstance()->set(RepeatCommand::class, static function (MacroCommand $macroCommand) {
            return new RepeatCommand($macroCommand);
        });

        IoC::getInstance()->set(
            LinearMotionMacroCommand::class,
            function (UObject $obj) use ($setUp) {
                $macro = new MacroCommand();
                $commands = array_map(
                    fn($className): Command => IoC::getInstance()->get($className, [$obj]),
                    $setUp[LinearMotionMacroCommand::class],
                );
                array_push($commands, IoC::getInstance()->get(RepeatCommand::class, [$macro]));
                $macro->setCommands($commands);

                return $macro;
            }
        );
    }
}
