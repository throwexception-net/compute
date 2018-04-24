<?php

namespace ThrowExceptionNet\Compute\Methods;

use function ThrowExceptionNet\Compute\f;

/**
 * Class Methods
 * @package ThrowExceptionNet\Compute\Methods
 * @property array ARITY
 * @property array ALIAS
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


        if (defined('static::ALIAS')) {
            $ALIAS = static::ALIAS;
            if (isset($ALIAS[$name])) {
                $name = static::ALIAS[$name];
            }
        }

        $ARITY = static::ARITY;
        if (isset($ARITY[$name])) {
            return f([static::$instance, $name], static::ARITY[$name]);
        }
        return false;
    }
}
