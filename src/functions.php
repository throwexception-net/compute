<?php

namespace ThrowExceptionNet\Compute;

/**
 * @param null|callable $fn
 * @param null|int $arity
 * @return Compute|Wrapper
 */
function f($fn = null, $arity = null)
{
    if ($fn === null) {
        return Compute::getInstance();
    }
    return new Wrapper($fn, false, $arity);
}

/**
 * @param null|callable $fn
 * @param null|int $arity
 * @return Compute|Wrapper
 */
function fr($fn = null, $arity = null)
{
    if ($fn === null) {
        return Compute::getInstance();
    }
    return new Wrapper($fn, true, $arity);
}

/**
 * @param bool|integer|float|string|array|null| $val
 * @return Ref
 */
function ref(&$val)
{
    return new Ref($val);
}
