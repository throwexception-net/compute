<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Wrapper;

/**
 * Class ArrayMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 *
 */
class ArrayMethods
{
    use MethodCollection;

    const ARITY = [
        'classify' => 2,
    ];

    /**
     * @param callable $callback
     * @param array $array
     * @return array|Wrapper
     */
    public function classify($callback = null, $array = null)
    {
        $result = [];
        $to = function ($class, $item) use (&$result) {
            if (!isset($result[$class])) {
                $result[$class] = [];
            }
            $result[$class][] = $item;
        };
        foreach ($array as $key => $item) {
            $callback($to, $item, $key, $result);
        }
        return $result;
    }
}
