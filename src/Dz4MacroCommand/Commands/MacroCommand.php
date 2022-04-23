<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\CommandException;

class MacroCommand implements Command
{
    protected array $commands;

    public function setCommands(array $commands)
    {
        $this->commands = $commands;
    }

    public function execute()
    {
        /** @var Command $command */
        foreach ($this->commands as $command) {
            try {
                $command->execute();
            } catch (CommandException $exception) {
                // Здесь надо что-то сделать с исключением, но как я понял это выходит за рамки данного задания
                break;
            }
        }
    }
}
