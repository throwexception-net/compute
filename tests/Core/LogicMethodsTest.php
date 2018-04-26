<?php

namespace ThrowExceptionNet\Compute\Tests\Misc;

use ThrowExceptionNet\Compute\Tests\TestCase;
use function ThrowExceptionNet\Compute\f;

class LogicMethodsTest extends TestCase
{
    /**
     * @test
     */
    public function and_should_work()
    {
        $this->assertTrue(f()->and(true, true));
        $this->assertTrue(f()->and(true)->i(true));
    }

    /**
     * @test
     */
    public function allPass_should_work()
    {
        $isOdd = function ($a) {
            return $a % 2 !== 0;
        };
        $gt10 = function ($a) {
            return $a > 10;
        };

        $aLtb = function ($a, $b) {
            return $a < $b;
        };

        $test = f()->allPass([$gt10, $isOdd]);
        $this->assertTrue($test(19));
        $this->assertFalse($test(20));
        $this->assertFalse($test(9));
        $this->assertTrue(f()->allPass([])->i(2));

        $test2 = f()->allPass([$gt10, $aLtb]);
        $this->assertEquals(2, $test2->getArity());
        $this->assertTrue($test2(15, 20));
        $this->assertTrue($test2->i(15)->i(20));
    }

    /**
     * @test
     */
    public function anyPass_should_work()
    {
        $isOdd = function ($a) {
            return $a % 2 !== 0;
        };
        $gt10 = function ($a) {
            return $a > 10;
        };

        $aLtb = function ($a, $b) {
            return $a < $b;
        };

        $test = f()->anyPass([$gt10, $isOdd]);
        $this->assertTrue($test(9));
        $this->assertTrue($test(20));
        $this->assertFalse($test(8));
        $this->assertFalse(f()->anyPass([])->i(2));

        $test2 = f()->anyPass([$gt10, $aLtb]);
        $this->assertEquals(2, $test2->getArity());
        $this->assertTrue($test2(15, 20));
        $this->assertTrue($test2->i(15)->i(20));
    }

    /**
     * @test
     */
    public function both_should_work()
    {
        
    }
}
