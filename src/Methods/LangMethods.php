<?php

namespace ThrowExceptionNet\Compute\Methods;

use ThrowExceptionNet\Compute\Exceptions\UndefinedException;
use function ThrowExceptionNet\Compute\f;

class LangMethods
{
    use MethodCollection;

    const ARITY = [
        'path' => 2
    ];

    /**
     * @param array|string|\Traversable $path
     * @param null $a
     * @return mixed|null
     * @throws UndefinedException
     */
    public function path($path = [], $a = null)
    {
        if (is_string($path)) {
            $path = explode('.', $path);
        }
        /** @noinspection ForeachSourceInspection */
        foreach ($path as $p) {
            if (is_array($a) || $a instanceof \ArrayAccess) {
                $a = f()->offsetGetOrUndef($p, $a);
            } else {
                $a = f()->getOrUndef($p, $a);
            }
        }
        return $a;
    }
}
