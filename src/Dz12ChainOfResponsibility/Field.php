<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

class Field extends AbstractField
{
    public int $fieldSize;

    public array $objectList;

    public function __construct(int $x, int $y)
    {
        $this->fieldSize = $this->cellSize;
        parent::__construct($x, $y);
    }

    public function getCurrentField(int $x, int $y): ?Field
    {
        if ($this->theObjectOnField($x, $y)) {
            return $this;
        }

        return null;
    }

    public function addObject(PositionDetectable $object)
    {
        $this->objectList[$object->getId()] = $object;
    }

    public function removeObject(int $objectId)
    {
        unset($this->objectList[$objectId]);
    }

    public function isTheSame(Field $field): bool
    {
        return $this->x == $field->x && $this->y == $field->y;
    }
}
