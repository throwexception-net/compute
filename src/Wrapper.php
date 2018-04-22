<?php


namespace ThrowExceptionNet\Compute;


use ThrowExceptionNet\Compute\Exceptions\BadMethodCallException;
use ThrowExceptionNet\Compute\Exceptions\InvalidArgumentException;

class Wrapper
{
    /**
     * @var int[]
     */
    protected static $arityCache = [];

    const PROPERTIES = [
        'fn',
        'arity',
        'isMethod',
        'isStatic',
        'useMagic',
        'useInvoke',
        'reverse',
        'args',
        'key'
    ];

    /**
     * @var callable
     */
    protected $fn;

    /**
     * @var integer|null;
     */
    protected $arity;

    protected $isMethod = false;

    protected $isStatic = false;

    protected $useMagic = false;

    protected $useInvoke = false;

    protected $reverse = true;

    protected $args = [];

    /**
     * @var bool|string 用于查找缓存的 arity
     */
    protected $key = false;

    /**
     * @return int
     */
    protected function getArity()
    {
        if ($this->arity !== null) {
            return $this->arity;
        }

        if ($this->useMagic) {
            return $this->arity = 0;
        }

        if (isset(self::$arityCache[$this->key])) {
            return self::$arityCache[$this->key];
        }

        try {
            if ($this->isMethod) {
                $this->arity = (new \ReflectionMethod($this->fn[0], $this->fn[1]))->getNumberOfRequiredParameters();
            } else if ($this->useInvoke) {
                $this->arity = (new \ReflectionMethod(get_class($this->fn), '__invoke'))->getNumberOfRequiredParameters();
            } else {
                $this->arity = (new \ReflectionFunction($this->fn))->getNumberOfRequiredParameters();
            }
        } catch (\ReflectionException $e) {
            return $this->arity = 0;
        }

        if ($this->key !== false) {
            self::$arityCache[$this->key] = $this->arity;
        }
        return $this->arity;
    }

    protected function initWithFn($fn)
    {
        if (is_string($fn)) {
            $this->key = $fn;
            if (strpos($fn, '::') !== false) {
                $fn = explode('::', $fn);
            }
        }
        if (is_array($fn)) {
            if (count($fn) < 2 || !is_string($fn[1])) {
                throw new InvalidArgumentException('Argument $fn should be a callable.');
            }
            $this->isMethod = true;
            if (is_string($fn[0])) {
                $this->isStatic = true;
                $this->key = $fn[0] . '::' . $fn[1];
            } else {
                $this->key = get_class($fn[0]) . '::' . $fn[1];
            }
        } else if (is_object($fn) && !($fn instanceof \Closure)) {
            $this->useInvoke = true;
            $this->key = get_class($fn);
        }
        return $fn;
    }

    protected function clones($args = null)
    {
        $next = clone $this;
        if ($args !== null) {
            $next->args = $args;
        }
        return $next;
    }

    protected function getMergedArgs($args)
    {
        $merged = [];
        foreach ($this->args as $k => $argument) {
            $merged[$k] = count($args) > 0 && Compute::isMe($argument)
                ? array_shift($args)
                : $argument;
        }
        $hasRefArg = false;
        $merged = array_merge($merged, $args);

        return [$merged, array_reduce($merged, function ($acc, $item) use (&$hasRefArg) {
            if ($item instanceof Ref) $hasRefArg = true;
            return Compute::isMe($item) ? $acc : $acc + 1;
        }, 0), $hasRefArg];
    }

    protected function initFrom(self $wrapper)
    {
        foreach (self::PROPERTIES as $property) {
            $this->$property = $wrapper->$property;
        }
    }

    public function __construct($fn, $reverse = false, $arity = null)
    {
        if ($fn instanceof self) {
            $this->initFrom($fn);
            return;
        }

        $this->arity = $arity;
        $this->reverse = $reverse;

        $fn = $this->initWithFn($fn);

        if (!is_callable($fn)) {
            if ($this->isMethod) {
                if ($this->isStatic && method_exists($fn[0], '__callStatic')) {
                    $this->useMagic = true;
                } else if (is_object($fn[0]) && method_exists($fn[0], '__call')) {
                    $this->useMagic = true;
                }
            }
            throw new InvalidArgumentException('Argument $fn should be a callable.');
        }

        $this->fn = $fn;
    }


    /**
     * @param array ...$args
     * @return Wrapper
     */
    public function __invoke(...$args)
    {
        list($merged, $k, $hasRefArg) = $this->getMergedArgs($args);

        if (count($merged) > $k || $k < $this->getArity()) {
            return $this->clones($merged);
        }

        if ($this->reverse) {
            $merged = array_reverse($merged);
        }

        $fn = $this->fn;

        return $hasRefArg
            ? $fn(...Ref::resolve($merged))
            : $fn(...$merged);
    }

    public function invoke()
    {
        return $this(...func_get_args());
    }

    public function i()
    {
        return $this(...func_get_args());
    }

    public function call($newThis, ...$args)
    {
        $new = $this->bindTo($newThis);
        return $new(...$args);
    }

    public function bindTo($newThis, $newScope = 'static')
    {
        $new = $this->clones();
        if ($this->useMagic) {
            throw new BadMethodCallException('Can not bind when $fn use magic __call() or __callStatic().');
        }
        $new->fn = \Closure::fromCallable($new->fn)->bindTo($newThis, $newScope);
        return $new;
    }
}