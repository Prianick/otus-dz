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
     * @return float[]|int[]
     */
    public function solve(float $a, float $b, float $c): array
    {
        if (bccomp($a, 0) === 0) {
            throw new \Exception('a can\'t be zero');
        }
        $D = $b * $b - 4 * $a * $c;
        if (bccomp($D, 0) < 0) {
            return [];
        }
        $x1 = (-$b - sqrt($D)) / (2 * $a);
        $x2 = (-$b + sqrt($D)) / (2 * $a);

        return [$x1, $x2];
    }
}
