<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

use OtusDZ\Src\Dz4MacroCommand\Commands\Command;
use OtusDZ\Src\Dz5IoC\IoC;

class SetCurrentFieldCommand implements Command
{
    public PositionDetectable $o;

    public function __construct(PositionDetectable $o)
    {
        $this->o = $o;
    }

    public function execute()
    {
        /** @var FieldSet $mainFieldSet */
        $mainFieldSet = IoC::resolve(AbstractField::MAIN_FIELD_SET);
        $newMainField = $mainFieldSet->getCurrentField($this->o->getX(), $this->o->getY());

        $prevMainField = $this->o->getCurrentField();
        if (!$newMainField->isTheSame($prevMainField)) {
            $prevMainField->removeObject($this->o->getId());
            $newMainField->addObject($this->o);
            $this->o->setCurrentField($newMainField);
        }

        /** @var FieldSet $secondFieldSet */
        $secondFieldSet = IoC::resolve(AbstractField::SECOND_FIELD_SET);
        $newSecondField = $secondFieldSet->getCurrentField($this->o->getX(), $this->o->getY());

        $prevSecondField = $this->o->getCurrentSecondaryField();
        if (!$newSecondField->isTheSame($prevSecondField)) {
            $prevSecondField->removeObject($this->o->getId());
            $newSecondField->addObject($this->o);
            $this->o->setCurrentSecondaryField($newSecondField);
        }
    }
}
