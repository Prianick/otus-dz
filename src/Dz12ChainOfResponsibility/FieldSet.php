<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

class FieldSet extends AbstractField
{
    public int $fieldSize;

    public array $child;

    public function getCurrentField(int $x, int $y): ?Field
    {
        if ($this->theObjectOnField($x, $y)) {
            /** @var AbstractField $children */
            foreach ($this->child as $children) {
                $field = $children->getCurrentField($x, $y);
                if (!empty($field)) {
                    return $field;
                }
            }
        }

        return null;
    }

    /**
     * @param int $currentFieldSize
     */
    public function setChild(int $currentFieldSize)
    {
        $this->fieldSize = $currentFieldSize;
        $x = $this->x;
        $newFieldSize = $this->fieldSize / 3;
        do {
            $y = $this->y;
            do {
                if ($newFieldSize > $this->cellSize) {
                    $fieldSet = new FieldSet($x, $y);
                    $fieldSet->setChild($newFieldSize);
                    $this->child[] = $fieldSet;
                } else {
                    $this->child[] = new Field($x, $y);
                }
                $y += $newFieldSize;
            } while ($y < ($this->fieldSize + $this->y));
            $x += $newFieldSize;
        } while ($x < ($this->fieldSize + $this->x));
    }
}
