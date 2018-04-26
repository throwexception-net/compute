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
        'true' => 1,
        'T' => 1,
        'false' => 1,
        'F' => 1,
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
