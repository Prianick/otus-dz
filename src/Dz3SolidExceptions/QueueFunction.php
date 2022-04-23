<?php

namespace OtusDZ\Src\Dz3SolidExceptions;

use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;

interface QueueFunction
{
    public function push(Command $command);

    public function get(): Command;
}
