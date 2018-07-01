<?php

namespace ThrowExceptionNet\Compute\Tests\Core;

use ThrowExceptionNet\Compute\Tests\TestCase;
use function ThrowExceptionNet\Compute\f;

class ListMethodsTest extends TestCase
{
    /**
     * @test
     */
    public function classify_should_work()
    {
        $classify = f()->classify(function ($to, $item) {
            if ($item > 10) {
                $to('gt10', $item);
            }
            if ($item > 100) {
                $to('gt100', $item);
            }
        });

        $this->assertEquals([
            'gt10' => [12, 32, 43, 132, 432 ,132],
            'gt100' => [132, 432 ,132]
        ], $classify([1, 2, 12, 32, 43, 132, 432 ,132]), '', 0, 10, true);
    }

    /**
     * @test
     */
    public function map_a_function_to_array_should_work()
    {
        $inc = function ($x) {
            return $x + 1;
        };
        $this->assertEquals([2, 3, 4, 5], f()->map($inc, [1, 2, 3, 4]));
    }

    /**
     * @test
     */
    public function map_some_traversable_should_work()
    {
        $mapTimes2 = f()->map(function ($x) {
            return $x * 2;
        });

        $expect = [2, 4, 6, 8];

        $generator = function () {
            for ($i = 1; $i <= 4; $i++) {
                yield $i;
            }
        };
        $this->assertEquals($expect, $mapTimes2($generator()));

        $arrayObject = new \ArrayObject([1, 2, 3, 4]);
        $this->assertEquals($expect, $mapTimes2($arrayObject));
    }

    /**
     * @test
     */
    public function map_object_should_get_an_array()
    {
        $mapAppendString = f()->map(function ($str) {
            return $str . ' is text.';
        });

        $o = (object) [
            'test1' => 'text1',
            'test2' => 'text2',
            'test3' => 'text3',
        ];

        $this->assertEquals([
            'test1' => 'text1 is text.',
            'test2' => 'text2 is text.',
            'test3' => 'text3 is text.',
        ], $mapAppendString($o));
    }

    public function map_a_string_should_get_an_array()
    {
        $mapAppendString = f()->map(function ($str) {
            return $str . '1';
        });

        $this->assertEquals([
            'a1', 'b1', 'c1', 'd1'
        ], $mapAppendString('abcd'));
    }
}
