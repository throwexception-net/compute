<?php

namespace ThrowExceptionNet\Compute\Methods;

use InvalidArgumentException;
use ThrowExceptionNet\Compute\Exceptions\UndefinedException;

class ObjectMethods
{
    use MethodCollection;

    const ARITY = [
        'get' => 2,
        'getOrUndef' => 2,
    ];

    protected function accessAndGet($turnExceptionToNull, $name, $a)
    {
        if (is_array($a) || $a instanceof \ArrayObject) {
            if (array_key_exists($name, $a)) {
                return $a[$name];
            }
            if ($turnExceptionToNull) {
                return null;
            }
            throw new UndefinedException();
        }
        if ($a instanceof \ArrayAccess) {
            if (isset($a[$name])) {
                return $a[$name];
            }
            if ($turnExceptionToNull) {
                return null;
            }
            throw new UndefinedException();
        }
        if (is_object($a)) {
            if (property_exists($a, $name)) {
                return $a->$name;
            }
            if ($turnExceptionToNull) {
                return null;
            }
            throw new UndefinedException();
        }
        throw new InvalidArgumentException('Can not access any props or offset of Argument $a.');
    }

    public function get($name = '', $object = null)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Argument $object must be an object');
        }
        if (property_exists($object, $name)) {
            return $object->$name;
        }
        return null;
    }

    public function getOrUndef($name = '', $object = null)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Argument $object must be an object');
        }
        if (property_exists($object, $name)) {
            return $object->$name;
        }
        throw new UndefinedException();
    }
}
