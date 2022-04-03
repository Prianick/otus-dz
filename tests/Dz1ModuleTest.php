<?php

namespace OzonDZ\Tests;

use OtusDZ\Src\Dz1ModuleTest\CustomFloat;
use OtusDZ\Src\Dz1ModuleTest\Equation;
use PHPUnit\Framework\TestCase;

class Dz1ModuleTest extends TestCase
{
    public function testHasNoRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1, 0, 1);
        $this->assertCount(0, $roots);
    }

    public function testNANINF()
    {
        $e = new Equation();

        /**
         * Генерирует набор параметров, включая один неверной
         *
         * @param $varPosition - куда вставить неверный параметр
         * @param $val - значение параметра
         * @return int[]
         */
        $genValues = function ($varPosition, $val): array {
            $values = [1, 1, 1];
            $values[$varPosition] = $val;

            return $values;
        };

        $wrongParamsSet = [INF, NAN];

        $noExceptionWasThrown = false;
        foreach ($wrongParamsSet as $val) {
            for ($i = 0; $i < 3; $i++) {
                try {
                    call_user_func_array([$e, 'solve'], $genValues($i, $val));
                    $noExceptionWasThrown = true;
                    break;
                } catch (\Exception $ex) {
                    continue;
                }
            }
        }

        $paramNames = ['$a', '$b', '$c', ''];

        $this->assertFalse(
            $noExceptionWasThrown,
            'Исключение не было выброшено для ' . $paramNames[$i] . ' со значением ' . $val
        );
    }

    public function testHasTwoRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1, 0, -1);
        $this->assertCount(2, $roots);
        $resultOfChecking = 0;
        $root1 = 1.0;
        $root2 = -1.0;
        foreach ($roots as $root) {
            if (CustomFloat::equals($root1, $root)) {
                $resultOfChecking++;
            }
            if (CustomFloat::equals($root2, $root)) {
                $resultOfChecking++;
            }
        }
        $this->assertEquals(2, $resultOfChecking);
    }

    public function testHasOneRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1, 2, 1);
        $this->assertTrue(CustomFloat::equals(-1.0, $roots[0]));
        $this->assertTrue(CustomFloat::equals(-1.0, $roots[1]));
    }

    public function testACanNotBeZero()
    {
        $e = new Equation();
        try {
            $e->solve(0, 2, 1);
        } catch (\Exception $ex) {
            $this->assertEquals('$a can\'t be zero', $ex->getMessage());
        }
    }
}
