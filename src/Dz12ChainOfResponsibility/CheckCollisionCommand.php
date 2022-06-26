<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

use OtusDZ\Src\Dz3SolidExceptions\QueueFunction;
use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;

class CheckCollisionCommand implements Command
{
    public PositionDetectable $o;

    public function __construct(PositionDetectable $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        $field = $this->o->getCurrentField();

        /** @var PositionDetectable $gameObject */
        foreach ($field->objectList as $gameObject) {
            if ($this->isThereCollisionOnMainFieldSet($gameObject)) {
                /** @var QueueFunction $queue */
                $queue = IoC::resolve('Queue');
                $queue->push(new ProcessCollisionCommand($this->o, $gameObject,AbstractField::MAIN_FIELD_SET));
            }
        }

        $field = $this->o->getCurrentSecondaryField();

        /** @var PositionDetectable $gameObject */
        foreach ($field->objectList as $gameObject) {
            if ($this->isThereCollisionOnSecondaryFieldSet($gameObject)) {
                /** @var QueueFunction $queue */
                $queue = IoC::resolve('Queue');
                $queue->push(new ProcessCollisionCommand($this->o, $gameObject, AbstractField::SECOND_FIELD_SET));
            }
        }
    }

    /**
     * @param PositionDetectable $gameObject
     * @return bool
     */
    public function isThereCollisionOnMainFieldSet(PositionDetectable $gameObject): bool
    {
        return $gameObject->getCurrentField()->x == $this->o->getCurrentField()->x
            && $gameObject->getCurrentField()->y == $this->o->getCurrentField()->y
            && $gameObject->getId() != $this->o->getId();
    }

    /**
     * @param PositionDetectable $gameObject
     * @return bool
     */
    public function isThereCollisionOnSecondaryFieldSet(PositionDetectable $gameObject): bool
    {
        return $gameObject->getCurrentSecondaryField()->x == $this->o->getCurrentSecondaryField()->x
            && $gameObject->getCurrentSecondaryField()->y == $this->o->getCurrentSecondaryField()->y
            && $gameObject->getId() != $this->o->getId();
    }
}
