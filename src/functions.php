<?php

namespace ThrowExceptionNet\Compute;

/**
 * @param null|callable $fn
 * @param null|int $arity
 * @param bool|null $reverse
 * @return Compute|Wrapper
 */
function f(callable $fn = null, $arity = null, $reverse = null)
{
    if ($fn === null) {
        return new Compute();
    }
    return new Wrapper($fn, $arity, $reverse);
}

/**
 * A placeholder
 * @return Compute
 */
function _()
{
    return new Compute();
}

/**
 * @param bool|integer|float|string|array|null| $val
 * @return Ref
 */
function ref(&$val)
{
    return new Ref($val);
}

/**
 * get required arity number of $fn
 *
 * @param callable $fn
 * @return bool|int
 */
function getArity(callable $fn)
{
    if (is_object($fn)) {
        if ($fn instanceof HasArity) {
            return $fn->getArity();
        }
        if (!($fn instanceof \Closure)) {
            $fn = [$fn, '__invoke'];
        }
    }

    if (is_string($fn) && strpos($fn, '::') !== false) {
        $fn = explode('::', $fn, 2);
    }

    //magic call
    if (is_array($fn) && !method_exists($fn[0], $fn[1])) {
        return false;
    }

    $refection = is_array($fn)
        ? new \ReflectionMethod($fn[0], $fn[1])
        : new \ReflectionFunction($fn);

    return $refection->getNumberOfRequiredParameters();
}
