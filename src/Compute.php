<?php

namespace ThrowExceptionNet\Compute;

use ThrowExceptionNet\Compute\Exceptions\BadMethodCallException;
use ThrowExceptionNet\Compute\Methods\DateMethods;
use ThrowExceptionNet\Compute\Methods\LangMethods;
use ThrowExceptionNet\Compute\Methods\ListMethods;
use ThrowExceptionNet\Compute\Methods\LogicMethods;
use ThrowExceptionNet\Compute\Methods\MathMethods;
use ThrowExceptionNet\Compute\Methods\ObjectMethods;
use ThrowExceptionNet\Compute\Methods\RelationMethods;
use ThrowExceptionNet\Compute\Methods\StringMethods;
use ThrowExceptionNet\Compute\Methods\UtilMethods;

/**
 * Class Placeholder
 * @package ThrowExceptionNet\Compute
 * @mixin ListMethods
 * @mixin RelationMethods
 * @mixin LangMethods
 * @mixin StringMethods
 * @mixin LogicMethods
 * @mixin UtilMethods
 * @mixin MathMethods
 * @mixin DateMethods
 * @mixin ObjectMethods
 */
class Compute
{
    const METHODS_ARITY = [
        ListMethods::class => ListMethods::ARITY,
        LangMethods::class => LangMethods::ARITY,
        StringMethods::class => StringMethods::ARITY,
        LogicMethods::class => LogicMethods::ARITY,
        RelationMethods::class => RelationMethods::ARITY,
        UtilMethods::class => UtilMethods::ARITY,
        MathMethods::class => MathMethods::ARITY,
        DateMethods::class => DateMethods::ARITY,
        ObjectMethods::class => ObjectMethods::ARITY,
    ];

    public static function getClassOfMethod($name)
    {
        foreach (self::METHODS_ARITY as $class => $arity) {
            if (isset($arity[$name])) {
                return $class;
            }
        }
        return false;
    }

    public static function isMe($object)
    {
        return is_object($object) && func_get_arg(0) instanceof self;
    }

    public function __call($name, $args)
    {
        if (!isset($this->$name)) {
            throw new BadMethodCallException('Method ' . $name . ' is non-exists');
        }
        $m = $this->getMethod($name);
        return $m(...$args);
    }

    public function __get($name)
    {
        return $this->getMethod($name);
    }

    public function __set($name, $value)
    {
        trigger_error('Compute is immutable.');
    }

    public function __isset($name)
    {
        return (bool) self::getClassOfMethod($name);
    }

    protected function getMethod($name)
    {
        $class = self::getClassOfMethod($name);
        if ($class === false) {
            throw new BadMethodCallException('Method ' . $name . ' is non-exists');
        }

        if (defined($class . '::ALIAS')) {
            $ALIAS = $class::ALIAS;
            if (isset($ALIAS[$name])) {
                $name = $class::ALIAS[$name];
            }
        }

        $arity = self::METHODS_ARITY[$class][$name];
        return f([new $class, $name], $arity);
    }

    public function __invoke($fn = null, $arity = null, $reverse = null)
    {
        return f($fn, $arity, $reverse);
    }
}
