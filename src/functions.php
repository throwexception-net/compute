<?php

namespace ThrowExceptionNet\Compute;

/**
 * @param null|callable $fn
 * @param null|int $arity
 * @param bool|null $reverse
 * @return Compute|Wrapper
 */
function f($fn = null, $arity = null, $reverse = null)
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
 * @param callable $fn
 * @return bool|int false for unable to get arity
 */
function getArity($fn)
{
    $isMethod = false;

    if (is_object($fn)) {
        if ($fn instanceof HasArity) {
            return $fn->getArity();
        }
        /** @noinspection MissingOrEmptyGroupStatementInspection */
        if ($fn instanceof \Closure) {
            //skip
        } elseif (method_exists($fn, '__invoke')) {
            $isMethod = true;
            $fn = [$fn, '__invoke'];
        } else {
            throw new \InvalidArgumentException(
                'Argument $fn should be a callable, an instance of ' . get_class($fn) . '  given.'
            );
        }
    }

    if (is_string($fn)) {
        if (strpos($fn, '::') !== false) {
            $fn = explode('::', $fn, 2);
        } elseif (!function_exists($fn)) {
            throw new \InvalidArgumentException(
                'Argument $fn should be a callable, string "' . $fn . '" given.'
            );
        }
    }

    if (is_array($fn)) {
        $isMethod = true;
        if ($isMagic = _validateCallableArray($fn)) {
            return false;
        }
    }

    $refection = $isMethod
        ? new \ReflectionMethod($fn[0], $fn[1])
        : new \ReflectionFunction($fn);

    return $refection->getNumberOfRequiredParameters();
}

/**
 * @param array $fn
 * @return bool true if using magic __call Or __callStatic
 */
function _validateCallableArray(array $fn)
{
    if (count($fn) !== 2) {
        throw new \InvalidArgumentException(
            'Argument $fn should be a callable, an invalid array given.'
        );
    }

    $class = is_object($fn[0]) ? get_class($fn[0]) : $fn[0];
    if (!is_string($fn[1]) || !class_exists($class)) {
        throw new \InvalidArgumentException(
            'Argument $fn should be a callable, an invalid array given.'
        );
    }

    if (!method_exists($class, $fn[1])) {
        if (is_object($fn[0]) && method_exists($class, '__call')) {
            return true;
        }
        if (method_exists($class, '__callStatic')) {
            return true;
        }
        throw new \InvalidArgumentException(
            'Argument $fn should be a callable, an invalid array given'
        );
    }
    return false;
}
