<?php

namespace ThrowExceptionNet\Compute;

class Ref
{
    public $val;

    /**
     * Ref constructor.
     * @param bool|integer|float|string|array|null $val
     */
    public function __construct(&$val)
    {
        $this->val = &$val;
    }

    /**
     * get the Ref
     * @return array|bool|float|int|null|string
     */
    public function &get()
    {
        return $this->val;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function resolve($array)
    {
        $resolved = [];
        foreach ($array as $key => $item) {
            if ($item instanceof self) {
                $resolved[$key] = &$item->val;
            } else {
                $resolved[$key] = $item;
            }
        }
        return $resolved;
    }
}
