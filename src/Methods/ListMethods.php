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
        'nth' => 2,
        'map' => 2,
        'reduce' => 2,
        'pluck' => 2,
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

    /**
     * @param string $offset
     * @param array|\ArrayObject|\ArrayAccess $list
     * @return mixed
     * @throws \ThrowExceptionNet\Compute\Exceptions\UndefinedException
     */
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

        throw new InvalidArgumentException('Argument $list must be an array, ArrayObject or implements \ArrayAccess');
    }

    /**
     * @param callable $fn
     * @param array|object|\Traversable $list
     * @return array
     * @throws \InvalidArgumentException
     */
    public function map(callable $fn = null, $list = null)
    {
        if (is_array($list)) {
            return array_map($fn, $list);
        }

        $result = [];

        if ($list instanceof \Traversable) {
            foreach ($list as $key => $item) {
                $result[$key] = $fn($item);
            }
            return $list;
        }

        if (is_object($list)) {
            foreach (get_object_vars($list) as $prop) {
                $result[$prop] = $fn($list->$prop);
            }
            return $result;
        }

        throw new \InvalidArgumentException('Argument $list must be one of array, object or implements \Traversable');
    }

    /**
     * @param callable $fn
     * @param mixed $acc
     * @param array|object|\Traversable $list
     * @return mixed
     */
    public function reduce(callable $fn = null, $acc = null, $list = null)
    {
        if (is_array($list)) {
            return array_reduce($list, $fn, $acc);
        }

        if ($list instanceof \Traversable) {
            foreach ($list as $item) {
                $acc = $fn($acc, $item);
            }
            return $acc;
        }

        if (is_object($list)) {
            foreach (get_object_vars($list) as $prop) {
                $acc = $fn($acc, $list->$prop);
            }
            return $acc;
        }

        throw new \InvalidArgumentException('Argument $list must be one of array, object or Traversable');
    }

    /**
     * @param string $name
     * @param array|object|\Traversable $collection
     * @return array
     */
    public function pluck($name = '', $collection = null)
    {
        return $this->map(f()->prop($name), $collection);
    }
}
