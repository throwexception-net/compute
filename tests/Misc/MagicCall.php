<?php

namespace ThrowExceptionNet\Compute\Tests\Misc;

class MagicCall
{
    protected $value;

    public function __construct($value = 1)
    {
        $this->value = $value;
    }

    public function __call($name, $arguments)
    {
        return $name . implode(',', $arguments);
    }

    public function noMagic($a, $b, $c)
    {
        return [$a, $b, $c];
    }

    public static function __callStatic($name, $arguments)
    {
        return $name . implode(',', $arguments);
    }

    public static function staticNoMagic($a, $b, $c)
    {
        return [$a, $b, $c];
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
