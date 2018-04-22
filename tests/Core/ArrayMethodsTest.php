<?php


namespace Core;


use ThrowExceptionNet\Compute\Tests\TestCase;
use function ThrowExceptionNet\Compute\f;

class ArrayMethodsTest extends TestCase
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
}