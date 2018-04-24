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
}
