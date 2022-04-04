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

    /**
     * Приводит число к нулю
     *
     * @param float $a
     * @return float
     */
    public static function toZeroIfLessEps(float $a): float
    {
        if (abs($a) < CustomFloat::EPS) {
            return 0.0;
        }

        return $a;
    }

    /**
     * @param float $a - радианы
     * @return float
     */
    public static function cos(float $a): float
    {
        return self::toZeroIfLessEps(cos($a));
    }

    /**
     * @param float $a - радианы
     * @return float
     */
    public static function sin(float $a): float
    {
        return self::toZeroIfLessEps(sin($a));
    }
}
