<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Wrapper;
use function ThrowExceptionNet\Compute\f;

/**
 * Class LogicMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 * @property callable and
 * @property callable et
 * @method bool|Wrapper and($a = true, $b = true)
 * @see LogicMethods::et()
 *
 *
 * @property callable or
 * @property callable ou
 * @method bool|Wrapper or($a = true, $b = true)
 * @see LogicMethods::ou()
 *
 * @property callable not
 */
class LogicMethods
{
    use MethodCollection;

    const ALIAS = [
        'and' => 'et',
        'or' => 'ou',
    ];

    const ARITY = [
        'and' => 2,
        'allPass' => 1,
        'anyPass' => 1,
        'both' => 2,
        'et' => 2,
        'or' => 2,
        'ou' => 2,
        'not' => 1,
    ];

    /**
     * @param callable[] $predicates
     * @return \Closure|Wrapper
     */
    public function allPass($predicates = [])
    {
        return f(function (...$args) use ($predicates) {
            foreach ($predicates as $predicate) {
                if (!$predicate(...$args)) {
                    return false;
                }
            }
            return true;
        }, $this->getMaxArityFromFns($predicates));
    }

    /**
     * @param callable[] $predicates
     * @return Wrapper
     */
    public function anyPass($predicates = [])
    {
        return f(function (...$args) use ($predicates) {
            foreach ($predicates as $predicate) {
                if ($predicate(...$args)) {
                    return true;
                }
            }
            return false;
        }, $this->getMaxArityFromFns($predicates));
    }

    /**
     * @param callable $fn1
     * @param callable $fn2
     * @return Wrapper
     */
    public function both($fn1, $fn2)
    {
        return f(function (...$args) use ($fn1, $fn2) {
            return $fn1(...$args) && $fn2(...$args);
        }, $this->getMaxArityFromFns([$fn1, $fn2]));
    }

    public function complement($fn)
    {
        return f(function (...$args) use ($fn) {
            return !$fn(...$args);
        }, f($fn)->getArity());
    }

    /**
     * Just "and" (in French)
     *
     * @param bool $a
     * @param bool $b
     * @return bool|Wrapper
     */
    public function et($a = true, $b = true)
    {
        return $a && $b;
    }

    /**
     * Just "or" (in French)
     *
     * @param bool $a
     * @param bool $b
     * @return bool|Wrapper
     */
    public function ou($a = true, $b = true)
    {
        return $a || $b;
    }

    public function not($a = true)
    {
        return !$a;
    }

    /**
     * @param array $fns
     * @return integer
     */
    protected function getMaxArityFromFns($fns = [])
    {
        return array_reduce($fns, function ($max, $fn) {
            if (!($fn instanceof Wrapper)) {
                $fn = f($fn);
            }
            return $fn->getArity() > $max
                ? $fn->getArity()
                : $max;
        }, 0);
    }
}
