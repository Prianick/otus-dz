<?php

namespace OtusDZ\Src\Dz4MacroCommand\Commands;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;
use OtusDZ\Src\Dz3SolidExceptions\IoC;
use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;

class RepeatCommand implements Command
{
    public MacroCommand $parentMacroCommand;

    public function __construct(MacroCommand $parentMacroCommand)
    {
        $this->parentMacroCommand = $parentMacroCommand;
    }

    public function execute()
    {
        /** @var QueueFunction $queue */
        $queue = IoC::getInstance()->get(QueueFunction::class);
        $queue->push($this->parentMacroCommand);
    }
}
