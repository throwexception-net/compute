<?php

namespace ThrowExceptionNet\Compute\Tests\Misc;

class StaticMagicClass
{
    public static function __callStatic($name, $arguments)
    {
        return $name . implode(',', $arguments);
    }
}
