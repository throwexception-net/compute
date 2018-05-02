<?php

namespace ThrowExceptionNet\Compute\Methods;

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
    use MethodCollection;

    const ARITY = [
        'true' => 0,
        'T' => 0,
        'false' => 0,
        'F' => 0,
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
}
