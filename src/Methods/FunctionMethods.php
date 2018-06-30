<?php

namespace ThrowExceptionNet\Compute\Methods;

use function ThrowExceptionNet\Compute\f;

/**
 * Class FunctionMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 * @property callable true
 * @property callable T
 * @method bool T()
 * @see FunctionMethods::true()
 *
 * @property callable false
 * @property callable F
 * @method bool F()
 * @see FunctionMethods::false()
 */
class FunctionMethods
{
    const ARITY = [
        'always' => 1,
        'apply' => 2,
        'true' => 0,
        'T' => 0,
        'false' => 0,
        'F' => 0,
        'memoizeWith' => 2,
    ];

    const ALIAS = [
        'T' => 'true',
        'F' => 'false',
    ];

    public function true()
    {
        return true;
    }

    public function false()
    {
        return false;
    }

    /**
     * @param $val
     * @return \Closure
     */
    public function always($val = 0)
    {
        return function () use ($val) {
            return $val;
        };
    }

    public function apply(callable $fn = null, $args = [])
    {
        return $fn(...$args);
    }
}
