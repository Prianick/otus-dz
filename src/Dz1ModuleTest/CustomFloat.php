<?php

namespace OtusDZ\Src\Dz1ModuleTest;

class CustomFloat
{
    public const EPS = 0.0000001;

    /**
     * Сравнивает два числа с точностью self::EPS.
     *
     * @param float $a
     * @param float $b
     * @return bool
     */
    public static function equals(float $a, float $b): bool
    {
        return abs($a - $b) < CustomFloat::EPS;
    }
}
