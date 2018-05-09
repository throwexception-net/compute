<?php

namespace ThrowExceptionNet\Compute\Tests\Core;

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
        $even = function ($x) {
            return $x % 2 === 0;
        };
        $gt10 = function ($x) {
            return $x > 10;
        };

        $c = f()->both($even, $gt10);
        $this->assertFalse($c(8));
        $this->assertFalse($c(11));
        $this->assertTrue($c(12));

        $between = function ($a, $b, $c) {
            return $a < $b && $b < $c;
        };

        $total20 = function ($a, $b, $c) {
            return ($a + $b + $c) === 20;
        };

        $c = f()->both($between, $total20);

        $this->assertTrue($c(4, 5, 11));
        $this->assertFalse($c(12, 2, 6));
        $this->assertFalse($c(5, 6, 15));
        $this->assertFalse($c(5)->i(6)->i(15));

        $a = f()->false;
        $x = 'not evaluated';

        $b = function () use (&$x) {
            $x = 'got evaluated';
        };

        f()->both($a, $b)->i();
        $this->assertEquals('not evaluated', $x);
    }

    /**
     * @test
     */
    public function complement_should_work()
    {
        $even = function ($a) {
            return $a % 2 === 0;
        };
        $c = f()->complement($even);
        $this->assertFalse($c(10));
        $this->assertTrue($c(11));

        $between = function ($a, $b, $c) {
            return $a < $b && $b < $c;
        };
        $c = f()->complement($between);
        $this->assertFalse($c(4, 5, 11));
        $this->assertTrue($c(12, 2, 6));
    }

    /**
     * @test
     */
    public function cond_should_work()
    {
        $c = f()->cond([
            [f()->equals(0), f()->always('0')],
            [f()->equals(100), f()->always('100')],
            [f()->true, f()->always('default')]
        ]);
        $this->assertEquals('0', $c(0));
        $this->assertEquals('100', $c(100));
        $this->assertEquals('default', $c(12345));
    }
}
