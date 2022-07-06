<?php

namespace OtusDZ\Src\Dz8Messaging;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;

interface RulesManager
{
    public function getCommand(string $playerType, string $operationAlias, array $args): Command;
}
