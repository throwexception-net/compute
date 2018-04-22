<?php


namespace ThrowExceptionNet\Compute\Methods;

use function ThrowExceptionNet\Compute\f;


/**
 * Class Methods
 * @package ThrowExceptionNet\Compute\Methods
 * @property array ARITY
 */
trait MethodCollection
{
    /**
     * @var null|static
     */
    protected static $instance = null;

    public static function getMethod($name)
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        $ARITY = static::ARITY;

        if (isset($ARITY[$name])) {
            return f([static::$instance, $name], static::ARITY[$name]);
        }
        return false;
    }
}