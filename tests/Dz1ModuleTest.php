<?php

namespace OzonDZ\Tests;

use OtusDZ\Src\Dz1ModuleTest\Equation;
use PHPUnit\Framework\TestCase;

class Dz1ModuleTest extends TestCase
{
    public function testHasNoRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1,0,1);
        $this->assertEmpty($roots);
    }

    public function testHasTwoRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1,0,-1);
        $this->assertCount(2, $roots);
        $this->assertContains(1, $roots);
        $this->assertContains(-1, $roots);
    }

    public function testHasOneRoots()
    {
        $e = new Equation();
        $roots = $e->solve(1,2,1);
        $this->assertEquals(-1, $roots[0]);
        $this->assertEquals(-1, $roots[1]);
    }

    public function testACanNotBeZero()
    {
        $e = new Equation();
        try {
            $roots = $e->solve(0,2,1);
        } catch (\Exception $e) {
            $this->assertEquals('a can\'t be zero', $e->getMessage());
        }
    }

    // public function test
}
