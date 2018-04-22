<?php


namespace Core;


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

        $ceiln3 = $ceil(-3);
        $this->assertEquals(1241000, $ceiln3(1241000));
        $this->assertEquals(1242000, $ceiln3(1241457));
        $this->assertEquals(1242000, $ceiln3(1241557));
        $this->assertEquals(1242000, $ceiln3(1241657));
        $this->assertEquals(1242000, $ceiln3(1241957));

        $this->assertEquals(-1241000, $ceiln3(-1241000));
        $this->assertEquals(-1241000, $ceiln3(-1241457));
        $this->assertEquals(-1241000, $ceiln3(-1241557));
        $this->assertEquals(-1241000, $ceiln3(-1241657));
        $this->assertEquals(-1241000, $ceiln3(-1241957));
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

        $floorn3 = $floor(-3);
        $this->assertEquals(1241000, $floorn3(1241000));
        $this->assertEquals(1241000, $floorn3(1241457));
        $this->assertEquals(1241000, $floorn3(1241557));
        $this->assertEquals(1241000, $floorn3(1241657));
        $this->assertEquals(1241000, $floorn3(1241957));

        $this->assertEquals(-1241000, $floorn3(-1241000));
        $this->assertEquals(-1242000, $floorn3(-1241457));
        $this->assertEquals(-1242000, $floorn3(-1241557));
        $this->assertEquals(-1242000, $floorn3(-1241657));
        $this->assertEquals(-1242000, $floorn3(-1241957));
    }
}