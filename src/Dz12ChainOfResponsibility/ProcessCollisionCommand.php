<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;


use OtusDZ\Src\Dz3SolidExceptions\Commands\Command;

class ProcessCollisionCommand implements Command
{
    public PositionDetectable $o1;
    public PositionDetectable $o2;
    public string $where;

    public function __construct(PositionDetectable $o1, PositionDetectable $o2, string $fieldSet)
    {
        $this->o1 = $o1;
        $this->o2 = $o2;
        $this->where = $fieldSet;
    }

    public function execute()
    {
        // здесь будем как-то обрабатывать коллизию
    }
}
