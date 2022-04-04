<?php

namespace OtusDZ\Src\SimpleSpaceWar\Errors;

class Errors
{
    public const POSITION_UNSET = 101;
    public const VELOCITY_UNSET = 102;

    public static array $errorMsgs = [
        self::POSITION_UNSET => 'Невозможно сдвинуть объект. Не установлена начальная позиция.',
        self::VELOCITY_UNSET => 'Невозможно сдвинуть объект. Не установлена мгновенная скорость.',
    ];
}
