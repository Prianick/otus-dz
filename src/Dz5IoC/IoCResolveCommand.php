<?php

namespace OtusDZ\Src\Dz5IoC;

use Closure;
use OtusDZ\Src\Dz4MacroCommand\Commands\Command;

class IoCResolveCommand implements Command
{
    protected Closure $func;

    public function __construct(Closure $func)
    {
        $this->func = $func;
    }

    public function execute()
    {
        call_user_func($this->func);
    }
}
