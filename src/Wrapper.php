<?php

namespace ThrowExceptionNet\Compute;

use ThrowExceptionNet\Compute\Exceptions\BadMethodCallException;

class Wrapper implements HasArity
{
    /**
     * @var callable
     */
    protected $fn;

    /**
     * @var integer|null|false;
     */
    protected $arity;

    /**
     * @var integer|null|false;
     */
    protected $fnArity = null;

    protected $reverse = true;

    protected $args = [];

    /**
     * @return int
     */
    public function getArity()
    {
        if (!$this->fnArity) {
            return 0;
        }
        if ($this->arity === null) {
            $k = array_reduce($this->args, function ($validNum, $arg) {
                return Compute::isMe($arg) ? $validNum : $validNum - 1;
            }, $this->fnArity);
            $this->arity = $k > 0 ? $k : 0;
        }
        return $this->arity;
    }

    protected function clones($args = null)
    {
        $clone = clone $this;
        if ($args !== null) {
            $clone->args = $args;
        }
        return $clone;
    }

    protected function getMergedArgs($args)
    {
        $merged = [];
        foreach ($this->args as $k => $arg) {
            $merged[$k] = count($args) > 0 && Compute::isMe($arg)
                ? array_shift($args)
                : $arg;
        }
        $hasRefArg = false;
        $merged = array_merge($merged, $args);

        return [$merged, array_reduce($merged, function ($acc, $arg) use (&$hasRefArg) {
            if ($arg instanceof Ref) {
                $hasRefArg = true;
            }
            return Compute::isMe($arg) ? $acc : $acc + 1;
        }, 0), $hasRefArg];
    }

    protected function setState(self $wrapper, $arity = null, $reverse = null)
    {
        $this->fn = $wrapper->fn;
        $this->args = $wrapper->args;
        $this->fnArity = $arity === null ? $wrapper->fnArity : $arity;
        $this->arity = null;
        $this->reverse = $reverse === null ? $wrapper->reverse : $reverse;
    }

    public function __construct(callable $fn, $arity = null, $reverse = null)
    {
        if ($fn instanceof self) {
            $this->setState($fn, $arity, $reverse);
        } else {
            if (is_string($fn) && strpos($fn, '::') !== false) {
                $fn = explode('::', $fn);
            }
            $this->fnArity = $arity === null ? getArity($fn) : $arity;
            $this->reverse = $reverse === true;
            $this->fn = $fn;
        }
    }

    /**
     * @param array ...$args
     * @return Wrapper|mixed
     */
    public function __invoke(...$args)
    {
        list($merged, $k, $hasRefArg) = $this->getMergedArgs($args);

        $arity = $this->fnArity === false ? 0 : $this->fnArity;

        if ($k < $arity || count($merged) > $k) {
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

    /**
     * @return Wrapper|mixed
     */
    public function invoke()
    {
        return $this(...func_get_args());
    }

    /**
     * @return Wrapper|mixed
     */
    public function i()
    {
        return $this(...func_get_args());
    }

    public function callOn($newThis, ...$args)
    {
        $new = $this->bindTo($newThis);
        return $new(...$args);
    }

    public function bindTo($newThis, $newScope = 'static')
    {
        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            throw new BadMethodCallException('Methods bindTo or callOn requires PHP >= 7.1.0');
        }
        $new = $this->clones();
        $new->fn = \Closure::fromCallable($new->fn)->bindTo($newThis, $newScope);
        return $new;
    }
}
