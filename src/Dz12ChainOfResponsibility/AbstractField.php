<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

abstract class AbstractField
{
    public const MAIN_FIELD_SET = 'mainFieldSet';
    public const SECOND_FIELD_SET = 'secondFieldSet';

    public int $x = 0;

    public int $y = 0;

    public int $cellSize = 100;

    public int $fieldSize;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    abstract public function getCurrentField(int $x, int $y): ?Field;

    protected function theObjectOnField(int $x, int $y): bool
    {
        $inHereByX = $x >= $this->x && $x < $this->fieldSize + $this->x;
        $inHereByY = $y >= $this->y && $y < $this->fieldSize + $this->y;

        return $inHereByX && $inHereByY;
    }
}
