<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Exceptions\UndefinedException;
use ThrowExceptionNet\Compute\Wrapper;
use function ThrowExceptionNet\Compute\f;

/**
 * Class LogicMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 * @property Wrapper and
 * @property Wrapper et
 * @method bool|Wrapper and($a = true, $b = true)
 * @see LogicMethods::et()
 *
 *
 * @property Wrapper or
 * @property Wrapper ou
 * @method bool|Wrapper or($a = true, $b = true)
 * @see LogicMethods::ou()
 *
 * @property callable $allPass
 * @see LogicMethods::allPass()
 *
 * @property callable $anyPass
 * @see LogicMethods::anyPass()
 *
 * @property callable $both
 * @see LogicMethods::both()
 *
 * @property callable $complement
 * @see LogicMethods::complement()
 *
 * @property callable $cond
 * @see LogicMethods::cond()
 *
 * @property callable $defaultTo
 * @see LogicMethods::defaultTo()
 *
 * @property callable $either
 * @see LogicMethods::either()
 *
 * @property callable $ifElse
 * @see LogicMethods::ifElse()
 *
 * @property callable $isEmpty
 * @see LogicMethods::isEmpty()
 *
 * @property callable $not
 * @see LogicMethods::not()
 *
 * @property callable $pathSatisfies
 * @see LogicMethods::pathSatisfies()
 *
 * @property callable $unless
 * @see LogicMethods::unless()
 *
 * @property callable $until
 * @see LogicMethods::until()
 *
 * @property callable $when
 * @see LogicMethods::when()
 */
class LogicMethods
{
    const ALIAS = [
        'and' => 'et',
        'or' => 'ou',
    ];

    const ARITY = [
        'and' => 2,
        'allPass' => 1,
        'anyPass' => 1,
        'both' => 2,
        'complement' => 1,
        'cond' => 1,
        'defaultTo' => 2,
        'either' => 2,
        'et' => 2,
        'ifElse' => 3,
        'isEmpty' => 1,
        'not' => 1,
        'or' => 2,
        'ou' => 2,
        'pathSatisfies' => 3,
        'unless' => 3,
        'until' => 3,
        'when' => 3
    ];

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

    /**
     * @param callable[] $predicates
     * @return Wrapper
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
    public function both($fn1 = null, $fn2 = null)
    {
        return f(function (...$args) use ($fn1, $fn2) {
            return $fn1(...$args) && $fn2(...$args);
        }, $this->getMaxArityFromFns([$fn1, $fn2]));
    }

    /**
     * @param callable $fn
     * @return Wrapper
     */
    public function complement($fn)
    {
        return f(function (...$args) use ($fn) {
            return !$fn(...$args);
        }, f($fn)->getArity());
    }

    /**
     * @param array $pairs
     * @return Wrapper
     */
    public function cond($pairs = [])
    {
        $fns = array_column($pairs, 0);
        return f(function (...$args) use ($pairs, $fns) {
            foreach ($fns as $i => $fn) {
                if ($fn(...$args)) {
                    return $pairs[$i][1](...$args);
                }
            }
            return null;
        }, $this->getMaxArityFromFns($fns));
    }

    /**
     * @param mixed $default
     * @param mixed $val
     * @return mixed
     */
    public function defaultTo($default = null, $val = null)
    {
        if ($val === null || is_nan($val)) {
            return $default;
        }
        return $val;
    }

    /**
     * @param callable $fn1
     * @param callable $fn2
     * @return Wrapper
     */
    public function either($fn1 = null, $fn2 = null)
    {
        return f(function (...$args) use ($fn1, $fn2) {
            return $fn1(...$args) || $fn2(...$args);
        }, $this->getMaxArityFromFns([$fn1, $fn2]));
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
     * @param callable $condition
     * @param callable $onTrue
     * @param callable $onFalse
     * @return Wrapper
     */
    public function ifElse($condition = null, $onTrue = null, $onFalse = null)
    {
        return f(function (...$args) use ($condition, $onTrue, $onFalse) {
            if ($condition(...$args)) {
                return $onTrue(...$args);
            }
            return $onFalse(...$args);
        }, $this->getMaxArityFromFns([$condition, $onTrue, $onFalse]));
    }

    /**
     * It is not same as empty()
     * @param mixed $a
     * @return bool false on $a is empty string or empty array|Countable|stdClass, otherwise true
     */
    public function isEmpty($a = [])
    {
        if ($a === '') {
            return true;
        }
        if (is_array($a) || $a instanceof \Countable) {
            return count($a) === 0;
        }
        if ($a instanceof \stdClass) {
            return count((array) $a) === 0;
        }
        return false;
    }

    /**
     * @param mixed $a
     * @return bool
     */
    public function not($a = true)
    {
        return !$a;
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

    /**
     * @param callable $predicate
     * @param array|string $path
     * @param array|object|\ArrayAccess $a
     * @return bool
     */
    public function pathSatisfies($predicate = null, $path = [], $a = null)
    {
        try {
            return $predicate(f()->path($path, $a));
        } catch (UndefinedException $e) {
            return false;
        }
    }

    /**
     * @param callable $predicate
     * @param callable $whenFalseFn
     * @param mixed $a
     * @return mixed
     */
    public function unless($predicate = null, $whenFalseFn = null, $a = null)
    {
        if ($predicate($a)) {
            return $a;
        }
        return $whenFalseFn($a);
    }

    /**
     * @param callable $predicate
     * @param callable $fn
     * @param mixed $initial
     * @return mixed
     */
    public function until($predicate = null, $fn = null, $initial = null)
    {
        while (!$predicate($initial)) {
            $initial = $fn($initial);
        }
        return $initial;
    }

    /**
     * @param callable $predicate
     * @param callable $whenTrueFn
     * @param mixed $a
     * @return mixed
     */
    public function when($predicate = null, $whenTrueFn = null, $a = null)
    {
        if ($predicate($a)) {
            return $whenTrueFn($a);
        }
        return $a;
    }
}
