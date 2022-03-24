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
        $this->assertEmpty($roots);
    }

    public function testNANINF()
    {
        $e = new Equation();

        try {
            $e->solve(2, INF, 1);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), '$a or $b or $c can\'t be NAN or INF');
        }

        try {
            $e->solve(NAN, 1, 1);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), '$a or $b or $c can\'t be NAN or INF');
        }
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
