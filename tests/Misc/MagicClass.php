<?php

namespace ThrowExceptionNet\Compute\Tests\Misc;

class MagicClass
{
    public function __call($name, $arguments)
    {
        return $name . implode(',', $arguments);
    }
}
