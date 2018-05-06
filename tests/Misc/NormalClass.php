<?php

namespace ThrowExceptionNet\Compute\Tests\Misc;

class NormalClass
{
    protected $value;

    public function __construct($value = 1)
    {
        $this->value = $value;
    }

    public function method($a, $b, $c)
    {
        return [$a, $b, $c];
    }

    public static function staticMethod($a, $b, $c)
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

    public function __invoke($a, $b, $c)
    {
        return [$a, $b, $c];
    }
}
