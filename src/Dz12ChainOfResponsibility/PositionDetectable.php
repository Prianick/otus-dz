<?php

namespace OtusDZ\Src\Dz12ChainOfResponsibility;

interface PositionDetectable
{
    public function getCurrentField(): Field;

    public function setCurrentField(Field $field);

    public function getCurrentSecondaryField(): Field;

    public function setCurrentSecondaryField(Field $field);

    public function getX(): int;

    public function getY(): int;

    public function getId(): int;

    public function setId(int $id): void;

    public function setX(int $x): void;

    public function setY(int $y): void;
}
