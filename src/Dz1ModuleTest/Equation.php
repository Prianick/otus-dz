<?php

namespace OtusDZ\Src\Dz1ModuleTest;

class Equation
{
    /**
     * Находит корен квадратного уравнения (ax^2 + bx + c = 0)
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @throws \Exception
     * @return array|float[]|int[]
     */
    public function solve(float $a, float $b, float $c): array
    {
        if (
            is_infinite($a)
            || is_infinite($b)
            || is_infinite($c)
            || is_nan($a)
            || is_nan($b)
            || is_nan($c)
        ) {
            throw new \Exception('$a or $b or $c can\'t be NAN or INF');
        }

        if (CustomFloat::equals($a, 0.0)) {
            throw new \Exception('$a can\'t be zero');
        }

        $D = $b * $b - 4 * $a * $c;
        if ($D < 0.0) {
            // Дискриминант меньше нуля
            return [];
        }

        if (CustomFloat::equals($D, 0.0)) {
            // Дискриминант равен нулю.
            $x1 = $x2 = -$b / (2 * $a);
        } else {
            // Дискриминант больше нуля
            $x1 = (-$b - sqrt($D)) / (2 * $a);
            $x2 = (-$b + sqrt($D)) / (2 * $a);
        }

        return [$x1, $x2];
    }
}
