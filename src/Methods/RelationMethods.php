<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Wrapper;
use function ThrowExceptionNet\Compute\f;

class RelationMethods
{
    const ARITY = [
        'clamp' => 3,
        'countBy' => 2,
    ];

    const ALIAS = [
        'eq' => 'equals',
    ];

    /**
     * @param mixed $min
     * @param mixed $max
     * @param mixed $a
     * @return mixed|Wrapper
     * @see http://www.php.net/manual/en/language.operators.comparison.php
     */
    public function clamp($min = 0, $max = 1, $a = 0)
    {
        if ($a > $max) {
            return $max;
        }
        if ($a < $min) {
            return $min;
        }
        return $a;
    }

    /**
     * @param callable $fn
     * @param array|\Traversable $list
     * @return array|Wrapper
     */
    public function countBy($fn = null, $list = [])
    {
        $counted = [];
        foreach ($list as $item) {
            $key = $fn($item);
            if (isset($counted[$key])) {
                $counted[$key]++;
            } else {
                $counted[$key] = 1;
            }
        }
        return $counted;
    }

    /**
     * @param callable $predicate
     * @param array|\Traversable $list1
     * @param array|\Traversable $list2
     * @return array|Wrapper
     */
    public function differentWith($predicate = null, $list1 = [], $list2 = [])
    {
        $diff = [];
        foreach ($list1 as $item1) {
            foreach ($list2 as $item2) {
                if (!$predicate($item1, $item2)
                    && !in_array($item2, $diff, true)) {
                        $diff[] = $item2;
                }
            }
        }
        return $diff;
    }

    /**
     * @param callable $fn
     * @param mixed $a
     * @param mixed $b
     * @return bool|Wrapper
     */
    public function eqBy($fn = null, $a = 0, $b = 0)
    {
        return $fn($a) === $fn($b);
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool|Wrapper
     */
    public function equals($a = 0, $b = 0)
    {
        return $a === $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool|Wrapper
     */
    public function gt($a = 0, $b = 0)
    {
        return $a > $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return bool|Wrapper
     */
    public function gte($a = 0, $b = 0)
    {
        return $a >= $b;
    }

    public function identical($a = 0, $b = 0)
    {
        if (is_nan($a) && is_nan($b)) {
            return true;
        }
        return $a === $b;
    }

    /**
     * @param mixed (required) $value
     * @return mixed
     */
    public function identity($value)
    {
        return $value;
    }

    /**
     * @param callable $predicate
     * @param array|\Traversable $list1
     * @param array|\Traversable $list2
     * @return array
     */
    public function innerJoin($predicate = null, $list1 = [], $list2 = [])
    {
        $result = [];
        foreach ($list1 as $item1) {
            foreach ($list2 as $item2) {
                if ($predicate($item1, $item2)) {
                    $result[] = $item1;
                    break;
                }
            }
        }
        return $result;
    }

    public function intersection($list1 = [], $list2 = [])
    {
        $result = [];
        foreach ($list1 as $item1) {
            foreach ($list2 as $item2) {
                if ($item1 === $item2
                    && !\in_array($item2, $result, true)) {
                    $result[] = $item2;
                }
            }
        }
        return $result;
    }

    public function lt($a = 0, $b = 0)
    {
        return $a < $b;
    }

    public function lte($a = 0, $b = 0)
    {
        return $a <= $b;
    }

    public function max($a = 0, $b = 0)
    {
        return $a > $b ? $a : $b;
    }

    /**
     * @param callable $fn
     * @param mixed $a
     * @param mixed $b
     * @return mixed
     */
    public function maxBy($fn = null, $a = 0, $b = 0)
    {
        $a = $fn($a);
        $b = $fn($b);
        return $a > $b ? $a : $b;
    }

    /**
     * @param mixed $a
     * @param mixed $b
     * @return mixed
     */
    public function min($a = 0, $b = 0)
    {
        return $a < $b ? $a : $b;
    }

    /**
     * @param callable $fn
     * @param mixed $a
     * @param mixed $b
     * @return mixed
     */
    public function minBy($fn = null, $a = 0, $b = 0)
    {
        $a = $fn($a);
        $b = $fn($b);
        return $a < $b ? $a : $b;
    }

    /**
     * @param array|string|\ArrayAccess $path
     * @param mixed $val
     * @param mixed $a
     * @return bool
     */
    public function pathEq($path = [], $val = null, $a = null)
    {
        return f()->pathSatisfies(f()->equals($val), $path, $a);
    }
}
