<?php

namespace ThrowExceptionNet\Compute;

use ThrowExceptionNet\Compute\Exceptions\BadMethodCallException;
use ThrowExceptionNet\Compute\Methods\ArrayMethods;
use ThrowExceptionNet\Compute\Methods\DateMethods;
use ThrowExceptionNet\Compute\Methods\LangMethods;
use ThrowExceptionNet\Compute\Methods\LogicMethods;
use ThrowExceptionNet\Compute\Methods\MathMethods;
use ThrowExceptionNet\Compute\Methods\ObjectMethods;
use ThrowExceptionNet\Compute\Methods\RelationMethods;
use ThrowExceptionNet\Compute\Methods\StringMethods;
use ThrowExceptionNet\Compute\Methods\UtilMethods;

/**
 * Class Placeholder
 * @package ThrowExceptionNet\Compute
 * @mixin ArrayMethods
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

    const METHOD_CLASSES = [
        ArrayMethods::class,
        LangMethods::class,
        StringMethods::class,
        LogicMethods::class,
        RelationMethods::class,
        UtilMethods::class,
        MathMethods::class,
        DateMethods::class,
        ObjectMethods::class
    ];

    /**
     * @var null|Compute
     */
    protected static $instance = null;

    /**
     * @var Wrapper[]
     */
    protected static $methodCache = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @return Compute
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return static::$instance;
    }

    public static function isMe()
    {
        $n = func_num_args();
        if ($n === 1) {
            return func_get_arg(0) === self::$instance;
        }

        $anythings = func_get_args();
        foreach ($anythings as $thing) {
            if ($thing === self::$instance) {
                return true;
            }
        }
        return false;
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
        if (!isset($this->$name)) {
            throw new BadMethodCallException('Method ' . $name . ' is non-exists');
        }
        return $this->getMethod($name);
    }

    public function __set($name, $value)
    {
        trigger_error('Compute is immutable.');
    }

    public function __isset($name)
    {
        return $this->getMethod($name) !== false;
    }

    protected function getMethod($name)
    {
        if (!isset(self::$methodCache[$name])) {
            $m = false;
            foreach (self::METHOD_CLASSES as $class) {
                $m = $class::getMethod($name);
                if ($m !== false) {
                    self::$methodCache[$name] = $m;
                    break;
                }
            }
            if ($m === false) {
                return false;
            }
        }

        return self::$methodCache[$name];
    }
}
