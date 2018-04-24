<?php

namespace Core;

use DivisionByZeroError;
use PHPUnit_Framework_Error;
use ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException;
use ThrowExceptionNet\Compute\Tests\TestCase;
use function ThrowExceptionNet\Compute\f;

class MathMethodsTest extends TestCase
{
    /**
     * @test
     */
    public function add_should_work()
    {
        $this->assertEquals(3, f()->add(1, 2));
        $this->assertEquals(3, f()->add('1', '2'));
        $this->assertEquals(3, f()->add(1)->i(2));
        $this->assertEquals([1, 2], f()->add([1], [2]));
        $this->assertEquals([1, 2], f()->add([1])->i([2]));
    }

    /**
     * @test
     */
    public function compare_should_work()
    {
        $this->assertEquals(0, f()->compare(1, 1));
        $this->assertEquals(1, f()->compare(2, 1));
        $this->assertEquals(-1, f()->compare(1, 2));
        $this->assertEquals(0, f()->compare('a', 'a'));
        $this->assertEquals(-1, f()->compare('A', 'a'));
        $this->assertEquals(1, f()->compare('a', 'A'));
        $this->assertEquals(-1, f()->compare('1', '01'));
    }

    /**
     * @test
     */
    public function dec_should_work()
    {
        $this->assertEquals(1, f()->dec(2));
        $this->assertEquals(-1, f()->dec(0));
    }

    /**
     * @test
     */
    public function divide_should_work()
    {
        $this->assertEquals(1 / 2, f()->divide(1, 2));
        $this->assertEquals(1 / 3, f()->divide(1, 3));
    }

    /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Division by zero
     */
    public function divide_by_zero_should_trigger_error()
    {
        f()->divide(1, 0);
    }

    /**
     * @test
     */
    public function inc_should_work()
    {
        $this->assertEquals(1, f()->inc(0));
        $this->assertEquals(0, f()->inc(-1));
    }

    /**
     * @test
     */
    public function mean_should_work()
    {
        $this->assertEquals(3, f()->mean([1, 2, 3, 4, 5]));
        $this->assertEquals(0, f()->mean([]));
    }

    /**
     * @test
     */
    public function median_should_work()
    {
        $this->assertEquals(3, f()->median([5, 2, 3, 1, 4]));
        $this->assertEquals((3 + 4) / 2, f()->median([5, 2, 3, 1, 4, 6]));
        $this->assertEquals(1, f()->median([1]));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Argument $numbers should not be empty.
     */
    public function media_empty_should_throw()
    {
        f()->median([]);
    }

    /**
     * @test
     */
    public function mod_should_work()
    {
        $this->assertEquals(5 % 4, f()->mod(5, 4));
        $this->assertEquals(3 % 4, f()->mod(3, 4));
        $this->assertEquals(3.2 % 4.5, f()->mod(3.2, 4.5));
    }

    /**
     * @test
     * @expectedException DivisionByZeroError
     * @expectedExceptionMessage Modulo by zero
     * @requires PHP 7.0
     */
    public function mod_by_zero_should_throw_error()
    {
        f()->mod(4, 0);
    }

    /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Division by zero
     * @requires PHP =5.6
     */
    public function mod_by_zero_should_trigger_error()
    {
        f()->mod(4, 0);
    }

    /**
     * @test
     */
    public function multiply_should_work()
    {
        $this->assertEquals(6, f()->multiply(2, 3));
        $this->assertEquals(6, f()->multiply(3, 2));
        $this->assertEquals(0, f()->multiply(0, 2));
    }

    /**
     * @test
     */
    public function negation_should_work()
    {
        $this->assertEquals(-1, f()->negation(1));
        $this->assertEquals(1, f()->negation(-1));
        $this->assertEquals(-.1, f()->negation(.1));
        $this->assertEquals(0, f()->negation(0));
        $this->assertEquals(-1, f()->negation('1'));
    }

    /**
     * @test
     */
    public function precision_half_should_work()
    {
        $halfUp = f()->precision('half_up');
        $halfUp0 = $halfUp(0);

        $this->assertEquals(2, $halfUp0(1.5));
        $this->assertEquals(-2, $halfUp0(-1.5));
        $this->assertEquals(3, $halfUp0(3.4));
        $this->assertEquals(4, $halfUp0(3.6));
        $this->assertEquals(2, $halfUp0(1.95583));

        $this->assertEquals(1242000, $halfUp(-3, 1241757));
        $this->assertEquals(5.05, $halfUp(2, 5.045));
        $this->assertEquals(5.06, $halfUp(2, 5.055));

        $halfDown = f()->precision('half_down');
        $halfDown0 = $halfDown(0);

        $this->assertEquals(1, $halfDown0(1.5));
        $this->assertEquals(-1, $halfDown0(-1.5));
        $this->assertEquals(3, $halfDown0(3.4));
        $this->assertEquals(4, $halfDown0(3.6));
        $this->assertEquals(2, $halfDown0(1.95583));

        $this->assertEquals(1242000, $halfDown(-3, 1241757));
        $this->assertEquals(5.04, $halfDown(2, 5.045));
        $this->assertEquals(5.05, $halfDown(2, 5.055));
    }

    /**
     * @test
     */
    public function precision_ceil_should_work()
    {
        $ceil = f()->precision('ceil');
        $ceil0 = $ceil(0);

        $this->assertEquals(3, $ceil0(3.0));
        $this->assertEquals(4, $ceil0(3.4));
        $this->assertEquals(4, $ceil0(3.5));
        $this->assertEquals(4, $ceil0(3.6));
        $this->assertEquals(4, $ceil0(3.9));

        $this->assertEquals(-3, $ceil0(-3.0));
        $this->assertEquals(-3, $ceil0(-3.4));
        $this->assertEquals(-3, $ceil0(-3.5));
        $this->assertEquals(-3, $ceil0(-3.6));
        $this->assertEquals(-3, $ceil0(-3.9));

        $this->assertEquals(3.1, $ceil(1, 3.10));
        $this->assertEquals(3.2, $ceil(1, 3.14));
        $this->assertEquals(3.2, $ceil(1, 3.15));
        $this->assertEquals(3.2, $ceil(1, 3.16));
        $this->assertEquals(3.2, $ceil(1, 3.19));

        $ceilN3 = $ceil(-3);
        $this->assertEquals(1241000, $ceilN3(1241000));
        $this->assertEquals(1242000, $ceilN3(1241457));
        $this->assertEquals(1242000, $ceilN3(1241557));
        $this->assertEquals(1242000, $ceilN3(1241657));
        $this->assertEquals(1242000, $ceilN3(1241957));

        $this->assertEquals(-1241000, $ceilN3(-1241000));
        $this->assertEquals(-1241000, $ceilN3(-1241457));
        $this->assertEquals(-1241000, $ceilN3(-1241557));
        $this->assertEquals(-1241000, $ceilN3(-1241657));
        $this->assertEquals(-1241000, $ceilN3(-1241957));
    }

    /**
     * @test
     */
    public function precision_floor_should_work()
    {
        $floor = f()->precision('floor');
        $floor0 = $floor(0);

        $this->assertEquals(3, $floor0(3.0));
        $this->assertEquals(3, $floor0(3.4));
        $this->assertEquals(3, $floor0(3.5));
        $this->assertEquals(3, $floor0(3.6));
        $this->assertEquals(3, $floor0(3.9));

        $this->assertEquals(-3, $floor0(-3.0));
        $this->assertEquals(-4, $floor0(-3.4));
        $this->assertEquals(-4, $floor0(-3.5));
        $this->assertEquals(-4, $floor0(-3.6));
        $this->assertEquals(-4, $floor0(-3.9));

        $this->assertEquals(3.1, $floor(1, 3.10));
        $this->assertEquals(3.1, $floor(1, 3.14));
        $this->assertEquals(3.1, $floor(1, 3.15));
        $this->assertEquals(3.1, $floor(1, 3.16));
        $this->assertEquals(3.1, $floor(1, 3.19));

        $floorN3 = $floor(-3);
        $this->assertEquals(1241000, $floorN3(1241000));
        $this->assertEquals(1241000, $floorN3(1241457));
        $this->assertEquals(1241000, $floorN3(1241557));
        $this->assertEquals(1241000, $floorN3(1241657));
        $this->assertEquals(1241000, $floorN3(1241957));

        $this->assertEquals(-1241000, $floorN3(-1241000));
        $this->assertEquals(-1242000, $floorN3(-1241457));
        $this->assertEquals(-1242000, $floorN3(-1241557));
        $this->assertEquals(-1242000, $floorN3(-1241657));
        $this->assertEquals(-1242000, $floorN3(-1241957));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Argument $mode must be one of ceil|floor|half_up|half_down
     */
    public function precision_unknow_should_throw()
    {
        f()->precision('asd', 1, 12);
    }

    /**
     * @test
     */
    public function sub()
    {
        $this->assertEquals(-1, f()->sub(1, 2));
        $this->assertEquals(1, f()->sub(2, 1));
        $this->assertEquals(0, f()->sub(1, 1));
    }
}
