<?php


namespace ThrowExceptionNet\Compute\Methods;


class UtilMethods
{
    use MethodCollection;

    const ARITY = [
        'identity' => 1,
    ];

    /**
     * @param mixed (required) $value
     * @return mixed
     */
    public function identity($value)
    {
        return $value;
    }

    public function lazy($evalFunc, ...$args)
    {

    }
}