<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException;
use ThrowExceptionNet\Compute\Exceptions\UndefinedException;
use ThrowExceptionNet\Compute\Wrapper;
use function ThrowExceptionNet\Compute\f;

/**
 * Class ArrayMethods
 * @package ThrowExceptionNet\Compute\Methods
 *
 *
 */
class ListMethods
{
    const ARITY = [
        'classify' => 2,
        'offsetGet' => 2,
        'offsetGetOrUndef' => 2,
        'nth' => 2
    ];

    /**
     * @param callable $callback
     * @param array $array
     * @return array|Wrapper
     */
    public function classify($callback = null, $array = null)
    {
        $result = [];
        $to = f(function ($class, $item) use (&$result) {
            if (!isset($result[$class])) {
                $result[$class] = [];
            }
            $result[$class][] = $item;
        }, 2);
        foreach ($array as $key => $item) {
            $callback($to, $item, $key, $result);
        }
        return $result;
    }

    /**
     * @param mixed $offset
     * @param array|string|\ArrayAccess $list
     * @return mixed|null
     */
    public function nth($offset = null, $list = [])
    {
        if (!is_string($list)
            || !is_array($list)
            || !($list instanceof \ArrayAccess)
        ) {
            return null;
        }
        if (isset($list[$offset])) {
            return $list[$offset];
        }
        return null;
    }

    /**
     * @param string $offset
     * @param array $list
     * @return mixed|null
     */
    public function offsetGet($offset = '', $list = [])
    {
        if (is_array($list)
            || $list instanceof \ArrayObject) {
            if (array_key_exists($offset, $list)) {
                return $list[$offset];
            }
            return null;
        }

        if ($list instanceof \ArrayAccess) {
            if ($list->offsetExists($offset)) {
                return $list->offsetGet($offset);
            }
            return null;
        }

        throw new InvalidArgumentException('Argument $list must be an array or implements \ArrayAccess');
    }

    public function offsetGetOrUndef($offset = '', $list = [])
    {
        if (is_array($list)
            || $list instanceof \ArrayObject) {
            if (array_key_exists($offset, $list)) {
                return $list[$offset];
            }
            throw new UndefinedException();
        }

        if ($list instanceof \ArrayAccess) {
            if ($list->offsetExists($offset)) {
                return $list->offsetGet($offset);
            }
            throw new UndefinedException();
        }

        throw new InvalidArgumentException('Argument $list must be an array or implements \ArrayAccess');
    }
}
