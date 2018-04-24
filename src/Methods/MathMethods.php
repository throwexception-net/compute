<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException;
use ThrowExceptionNet\Compute\Wrapper;

/**
 * Class MathMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 * @property callable $add
 * @property callable $compare
 * @property callable $dec
 * @property callable $divide
 * @property callable $inc
 * @property callable $mean
 * @property callable $median
 * @property callable $mod
 * @property callable $multiply
 * @property callable $negation
 * @property callable $precision
 * @property callable $sub
 */
class MathMethods
{
    use MethodCollection;

    const ARITY = [
        'add' => 2,
        'compare' => 2,
        'dec' => 1,
        'divide' => 2,
        'inc' => 1,
        'mean' => 1,
        'median' => 1,
        'mod' => 2,
        'multiply' => 2,
        'negation' => 1,
        'precision' => 3,
        'sub' => 2,
    ];

    /**
     * @param int|array (required) $a
     * @param int|array (required) $b
     * @return array|float|int|Wrapper
     */
    public function add($a = 0, $b = 0)
    {
        return is_numeric($a) ? $a + $b : array_merge($a, $b);
    }

    /**
     * @param int|string (required) $a
     * @param int|string (required) $b
     * @return int|Wrapper -1 0 1
     */
    public function compare($a = 0, $b = 0)
    {
        if ($a === $b) {
            return 0;
        }
        if ($a > $b) {
            return 1;
        }
        return -1;
    }

    /**
     * @param int|float (required) $a
     * @return int|float|Wrapper
     */
    public function dec($a = 1)
    {
        return --$a;
    }

    /**
     * @param int|float (required) $a
     * @param int|float (required) $b
     * @return int|float|Wrapper
     */
    public function divide($a = 0, $b = 1)
    {
        return $a / $b;
    }

    /**
     * @param int|float (required) $a
     * @return int|float|Wrapper
     */
    public function inc($a = 0)
    {
        return ++$a;
    }

    /**
     * @param array $numbers
     * @return float|int|Wrapper
     */
    public function mean($numbers = [])
    {
        $k = count($numbers);
        if ($k === 0) {
            return 0;
        }
        return array_sum($numbers) / $k;
    }

    /**
     * @param array $numbers
     * @return float|int|Wrapper
     * @throws \ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException
     */
    public function median($numbers = [])
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException('Argument $numbers should not be empty.');
        }
        sort($numbers);
        $i = count($numbers) / 2;
        if (is_float($i)) {
            return $numbers[(int) $i];
        }
        return ($numbers[$i - 1] + $numbers [$i]) / 2;
    }

    /**
     * @param int|float (required) $a
     * @param int|float (required) $b
     * @return int|float|Wrapper
     */
    public function mod($a = 0, $b = 1)
    {
        return $a % $b;
    }

    /**
     * @param int (required) $a
     * @param int (required) $b
     * @return int|float|Wrapper
     */
    public function multiply($a = 0, $b = 0)
    {
        return $a * $b;
    }

    /**
     * @param int|float (required) $value
     * @return int|float
     */
    public function negation($value)
    {
        return - $value;
    }

    /**
     * @param string $mode ceil|floor|half_up|half_down
     * @param int $precision
     * @param float|int $val
     * @return float|int|Wrapper
     * @throws \ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException
     */
    public function precision($mode = 'floor', $precision = 0, $val = 0)
    {
        switch ($mode) {
            case 'half_up':
                return round($val, $precision, PHP_ROUND_HALF_UP);
            case 'half_down':
                return round($val, $precision, PHP_ROUND_HALF_DOWN);
            case 'ceil':
                if (round($val, $precision, PHP_ROUND_HALF_UP) == $val) {
                    return $val;
                }
                return round($val + (0.1 ** $precision) * 0.5, $precision, PHP_ROUND_HALF_UP);
            case 'floor':
                if (round($val, $precision, PHP_ROUND_HALF_DOWN) == $val) {
                    return $val;
                }
                return round($val - (0.1 ** $precision) * 0.5, $precision, PHP_ROUND_HALF_DOWN);
        }

        throw new InvalidArgumentException('Argument $mode must be one of ceil|floor|half_up|half_down');
    }

    /**
     * @param int|float (required) $a
     * @param int|float (required) $b
     * @return int|float|Wrapper
     */
    public function sub($a = 0, $b = 0)
    {
        return $a - $b;
    }
}
