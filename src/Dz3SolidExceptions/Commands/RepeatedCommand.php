<?php

namespace OtusDZ\Src\Dz3SolidExceptions\Commands;


class RepeatedCommand implements Command
{
    public Command $originalCommand;

    private int $counter = 0;

    public function __construct(Command $command)
    {
        $this->originalCommand = $command;
    }

    public function execute()
    {
        $this->counter++;
        $this->originalCommand->execute();
    }

    public function getCount(): int
    {
        return $this->counter;
    }
}
