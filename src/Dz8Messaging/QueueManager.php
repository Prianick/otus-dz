<?php

namespace OtusDZ\Src\Dz8Messaging;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;

interface QueueManager
{
    public function pushCommand(Command $command);

    public function setGameId(int $gameId);
}
