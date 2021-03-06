<?php

namespace ThrowExceptionNet\Compute\Tests\Core;

use ThrowExceptionNet\Compute\Tests\Misc\MagicClass;
use ThrowExceptionNet\Compute\Tests\Misc\NormalClass;
use ThrowExceptionNet\Compute\Tests\Misc\StaticMagicClass;
use ThrowExceptionNet\Compute\Tests\TestCase;
use function ThrowExceptionNet\Compute\_;
use function ThrowExceptionNet\Compute\f;
use function ThrowExceptionNet\Compute\ref;

class FTest extends TestCase
{
    /**
     * @test
     */
    public function wrapper_is_callable()
    {
        $c = f(function () {
            return 1;
        });

        $this->assertTrue(is_callable($c));
        $this->assertEquals(1, $c());
        $this->assertEquals(1, $c->i());
        $this->assertEquals(1, $c->invoke());
    }

    /**
     * @test
     */
    public function wrap_wrapper_should_work()
    {
        $c = f(f(function () {
            return 1;
        }));

        $this->assertEquals(1, $c());
    }

    /**
     * @test
     * @requires PHP 7.1
     */
    public function bindTo_callOn_should_work()
    {
        $a = new NormalClass(1);
        $b = new NormalClass(2);

        $c1 = f([$a, 'getValue']);

        $this->assertEquals(1, $c1());
        $this->assertEquals(2, $c1->bindTo($b)->i());
        $this->assertEquals(2, $c1->callOn($b));
        $this->assertEquals(1, $c1());
    }

    /**
     * @test
     */
    public function wrap_ref_should_work()
    {
        $a = 1;
        $b = 1;
        $add = f(function (&$a, &$b, $x) {
            ++$a;
            $b += $x;
        })->i(ref($a))->i(ref($b));

        $add(2);
        $this->assertEquals(2, $a);
        $this->assertEquals(3, $b);

        $add(2);
        $this->assertEquals(3, $a);
        $this->assertEquals(5, $b);

        f(function (&$str) {
            $str = 'string';
        })->i(ref($str));
        $this->assertEquals('string', $str);

        $array = [3, 2, 1];
        f('sort')->i(ref($array));
        $this->assertEquals([1, 2, 3], $array);
    }

    /**
     * @test
     */
    public function f_with_arity_should_work()
    {
        $c = f(function ($a, $b = 2, $c = 3) {
            return [$a, $b, $c];
        }, 2);

        $this->assertEquals([1, 2, 3], $c(1, 2));
        $this->assertEquals([1, 2, 3], $c(1)->i(2));
        $this->assertEquals([1, 2, 4], $c(1)->i(2, 4));
        $this->assertEquals([1, 2, 4], $c(1, 2, 4));
    }

    /**
     * @test
     */
    public function create_wrapper_for_class_method_should_success()
    {

        $callables = [
            f([new MagicClass(), 'magic']),
            f([StaticMagicClass::class, 'magic']),
            f(StaticMagicClass::class . '::magic'),
        ];
        foreach ($callables as $c) {
            $this->assertEquals('magic', $c());
            $this->assertEquals('magic1', $c(1));
            $this->assertEquals('magic1,2', $c(1, 2));
            $this->assertEquals('magic1,2', $c(_(), 2)->i(1));
        }
        $callables = [
            f([new NormalClass, 'method']),
            f([NormalClass::class, 'staticMethod']),
        ];

        foreach ($callables as $c) {
            $this->assertEquals([1, 2, 3], $c(1, 2, 3));
            $this->assertEquals([1, 2, 3], $c(1)->i(2)->i(3));
            $this->assertEquals([1, 2, 3], $c(1, 2)->i(3));
        }
    }

    /**
     * @test
     */
    public function create_wrapper_for_function_should_success()
    {
        function test($a, $b, $c)
        {
            return [$a, $b, $c];
        }

        $c = f(__NAMESPACE__ . '\test');
        $this->assertEquals([1, 2, 3], $c(1, 2, 3));
        $this->assertEquals([1, 2, 3], $c(1)->i(2)->i(3));
        $this->assertEquals([1, 2, 3], $c(1, 2)->i(3));
    }

    /**
     * @test
     */
    public function f_curry_should_work()
    {
        $c = f(function ($a, $b, $c) {
            return [$a, $b, $c];
        });

        $this->assertEquals([1, 2, 3], $c(1, 2, 3));
        $this->assertEquals([1, 2, 3], $c(1)->i(2)->i(3));
        $this->assertEquals([1, 2, 3], $c(1, 2)->i(3));
        $cp = $c(1);
        $this->assertEquals([1, 2, 3], $cp(2, 3));
        $this->assertEquals([1, 2, 3], $cp(2, 3));
        $this->assertEquals([1, 2, 3], $c(1, _(), 3)->i(2));
        $this->assertEquals([1, 2, 3], $c(_(), _(), 3)->i(1, 2));
        $this->assertEquals([1, 2, 3], $c(_(), _(), 3)->i(_(), 2)->i(1));
        $this->assertEquals([1, 2, 3], $c(1, 2, _())->i(3));
    }

    /**
     * @test
     */
    public function f_curry_right_should_work()
    {
        $cr = f(function ($a, $b, $c) {
            return [$a, $b, $c];
        }, null, true);

        $this->assertEquals([3, 2, 1], $cr(1, 2, 3));
        $this->assertEquals([3, 2, 1], $cr(1)->i(2)->i(3));
        $this->assertEquals([3, 2, 1], $cr(1, 2)->i(3));
        $this->assertEquals([3, 2, 1], $cr(1)->i(2, 3));
        $this->assertEquals([3, 2, 1], $cr(1, _(), 3)->i(2));
        $this->assertEquals([3, 2, 1], $cr(_(), _(), 3)->i(1, 2));
        $this->assertEquals([3, 2, 1], $cr(_(), _(), 3)->i(_(), 2)->i(1));
        $this->assertEquals([3, 2, 1], $cr(1, 2, _())->i(3));
    }

    /**
     * @test
     */
    public function f_curry_with_optional_parameter_should_work()
    {
        $c = f(function ($a, $b, $c = 3, $d = 4) {
            return [$a, $b, $c, $d];
        });

        $this->assertEquals([1, 2, 3, 4], $c(1, 2));
        $this->assertEquals([1, 2, 3, 4], $c(1)->i(2));
        $this->assertEquals([1, 2, 5, 4], $c(1, 2, 5));
        $this->assertEquals([1, 2, 5, 6], $c(1)->i(2, 5, 6));
        $this->assertEquals([1, 2, 5, 6], $c(1, 2, 5, 6));

        $this->assertEquals([1, 2, 3, 4], $c(1, _())->i(2));
        $this->assertEquals([1, 2, 5, 4], $c(1)->i(_(), 5)->i(2));
        $this->assertEquals([1, 2, 5, 6], $c(1)->i(_(), 5, 6)->i(2));
        $this->assertEquals([1, 2, 5, 4], $c(1, _(), 5)->i(2));
        $this->assertEquals([1, 2, 5, 4], $c(1, 2, _())->i(5));
        $this->assertEquals([1, 2, 5, 4], $c(1, _(), _())->i(2, 5));
        $this->assertEquals([1, 2, 5, 6], $c(1, _(), 5, _())->i(2, 6));
        $this->assertEquals([1, 2, 5, 6], $c(1, 2, _(), _())->i(5, 6));
        $this->assertEquals([1, 2, 5, 6], $c(1, _(), _(), _())->i(2, 5, 6));
        $this->assertEquals([1, 2, 5, 6], $c(1, _(), _(), _())->i(2)->i(5)->i(6));
        $this->assertEquals([1, 2, 5, 6], $c(1, _(), _(), _())->i(2, 5)->i(6));
        $this->assertEquals([1, 2, 5, 6], $c(1, _(), _(), _())->i(2, 5)->i(_())->i(6));

        $this->assertEquals([1, 2, 3, 4], $c(1, 2, 3, 4, 5, 6, 7));
        $this->assertEquals([1, 2, 3, 4], $c(1, 2, 3, _(), 5, 6, 7)->i(4));
    }

    /**
     * @test
     */
    public function f_curry_right_with_optional_parameter_should_work()
    {
        $c = f(function ($a, $b, $c = 3, $d = 4) {
            return [$a, $b, $c, $d];
        }, null, true);

        //实际给多少参数，翻转多少参数
        $this->assertEquals([2, 1, 3, 4], $c(1, 2));
        $this->assertEquals([2, 1, 3, 4], $c(1)->i(2));
        $this->assertEquals([3, 2, 1, 4], $c(1, 2, 3));
        $this->assertEquals([5, 5, 2, 1], $c(1)->i(2, 5, 5));
        $this->assertEquals([5, 5, 2, 1], $c(1, 2, 5, 5));

        $this->assertEquals([2, 1, 3, 4], $c(1, _())->i(2));
        $this->assertEquals([5, 2, 1, 4], $c(1, _(), 5)->i(2));
        $this->assertEquals([5, 2, 1, 4], $c(1)->i(_(), 5)->i(2));
        $this->assertEquals([6, 5, 2, 1], $c(1)->i(_(), 5, 6)->i(2));
        $this->assertEquals([5, 2, 1, 4], $c(1, 2, _())->i(5));
        $this->assertEquals([5, 2, 1, 4], $c(1, _(), _())->i(2, 5));
        $this->assertEquals([6, 5, 2, 1], $c(1, _(), 5, _())->i(2, 6));
        $this->assertEquals([6, 5, 2, 1], $c(1, 2, _(), _())->i(5, 6));
        $this->assertEquals([6, 5, 2, 1], $c(1, _(), _(), _())->i(2, 5, 6));
        $this->assertEquals([6, 5, 2, 1], $c(1, _(), _(), _())->i(2)->i(5)->i(6));
        $this->assertEquals([6, 5, 2, 1], $c(1, _(), _(), _())->i(2, 5)->i(6));
        $this->assertEquals([6, 5, 2, 1], $c(1, _(), _(), _())->i(2, 5)->i(_())->i(6));

        //翻转所有给出的参数
        $this->assertEquals([7, 6, 5, 4], $c(1, 2, 3, 4, 5, 6, 7));
        $this->assertEquals([7, 6, 5, 4], $c(1, 2, 3, _(), 5, 6, 7)->i(4));
    }
}
