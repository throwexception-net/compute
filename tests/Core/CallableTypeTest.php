<?php

namespace ThrowExceptionNet\Compute\Tests\Core;

use PHPUnit_Framework_Error;
use ThrowExceptionNet\Compute\Tests\Misc\MagicClass;
use ThrowExceptionNet\Compute\Tests\Misc\NormalClass;
use ThrowExceptionNet\Compute\Tests\Misc\StaticMagicClass;
use ThrowExceptionNet\Compute\Tests\TestCase;
use TypeError;

class CallableTypeTest extends TestCase
{
    protected function callable_test(callable $callable)
    {
        return true;
    }

    public function callableDataProvider()
    {
        $closure = function ($a, $b, $c) {
            return [$a, $b, $c];
        };

        $userFunction = '\ThrowExceptionNet\Compute\Tests\Core\just_an_user_function';
        return [
            'string'                     => ['array_merge',                         [[1, 2], [3, 4]],   [1, 2, 3, 4]],
            'string with namespace'      => ['\array_merge',                        [[1, 2], [3, 4]],   [1, 2, 3, 4]],
            'user function'              => [$userFunction,                         [1, 2, 3],          [1, 2, 3]],

            'method'                     => [[new NormalClass, 'method'],           [1, 2, 3],          [1, 2, 3]],
            'static method'              => [[NormalClass::class, 'staticMethod'],  [1, 2, 3],          [1, 2, 3]],
            'string static method'       => [NormalClass::class . '::staticMethod', [1, 2, 3],          [1, 2, 3]],

            'magic method'               => [[new MagicClass, 'magic'],             [1, 2, 3],          'magic1,2,3'],
            'magic static method'        => [[StaticMagicClass::class, 'magic'],    [1, 2, 3],          'magic1,2,3'],
            'string magic static method' => [StaticMagicClass::class . '::magic',   [1, 2, 3],          'magic1,2,3'],

            'closure'                    => [$closure,                              [1, 2, 3],          [1, 2, 3]],
            'object with invoke'         => [new NormalClass,                       [1, 2, 3],          [1, 2, 3]]
        ];
    }

    /**
     * @dataProvider callableDataProvider
     * @test
     * @param $callable
     */
    public function is_valid_callable($callable)
    {
        $this->assertTrue($this->callable_test($callable));
        $this->assertTrue(is_callable($callable));
    }

    public function invalidCallableDataProvider()
    {
        return [
            'string' => ['some_invalid_function_name_dawe21s'],
            'array' => [['a', 'b']],
            'null' => [null],
            'too long array' => [[NormalClass::class, 'staticMethod', 'xxx']],
            'false' => [false],
            'true' => [true],
            '1' => [1],
            'object' => [new \stdClass()],
            'method not exists' => [[new NormalClass, 'someMethod']],
            'static method not exists' => [[NormalClass::class, 'someMethod']],
            'string static method non-exists' => [NormalClass::class . '::someMethod'],
            '__callStatic exists but call non-exists instance method' => [[new StaticMagicClass(), 'test']],
            '__call exists but call non-exists static method' => [[MagicClass::class, 'test']],
            '__call exists but call non-exists string static method' => [MagicClass::class . '::test']
        ];
    }

    /**
     * @dataProvider callableDataProvider
     * @test
     * @param $callable
     * @param $args
     * @param $return
     */
    public function callable_can_be_invoke_direct($callable, $args, $return)
    {
        $this->assertEquals($return, $callable(...$args));
    }

    /**
     * @dataProvider invalidCallableDataProvider
     * @test
     * @expectedException TypeError
     * @requires PHP 7.0
     * @param $callable
     */
    public function invalid_function_name_should_throw_error($callable)
    {
        $this->assertFalse(is_callable($callable));
        $this->callable_test($callable);
    }

    /**
     * @dataProvider invalidCallableDataProvider
     * @test
     * @expectedException PHPUnit_Framework_Error
     * @requires PHP =5.6
     * @param $callable
     */
    public function invalid_function_name_should_trigger_error($callable)
    {
        $this->assertFalse(is_callable($callable));
        $this->callable_test($callable);
    }
}

function just_an_user_function($a, $b, $c)
{
    return [$a, $b, $c];
}
