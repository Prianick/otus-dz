<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use Exception;
use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz4MacroCommand\CommandException;

class MacroCommand implements Command
{
    protected array $commands;

    public function setCommands(array $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @throws CommandException
     */
    public function execute()
    {
        /** @var Command $command */
        foreach ($this->commands as $command) {
            try {
                $command->execute();
            } catch (Exception $e) {
                throw new CommandException($e->getMessage());
            }
        }
    }
}
