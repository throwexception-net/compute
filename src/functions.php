<?php

namespace ThrowExceptionNet\Compute;


/**
 * @param null|callable $fn
 * @param null|int $arity
 * @param bool $reverse
 * @return Compute|Wrapper
 */
function f($fn = null, $arity = null, $reverse = false)
{
    if ($fn === null) {
        return Compute::getInstance();
    }
    return new Wrapper($fn, $arity, $reverse);
}

/**
 * A placeholder
 * @return Compute
 */
function _()
{
    return Compute::getInstance();
}

/**
 * @param bool|integer|float|string|array|null| $val
 * @return Ref
 */
function ref(&$val)
{
    return new Ref($val);
}
