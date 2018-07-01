<?php

namespace ThrowExceptionNet\Compute;

class StringIterator implements \Iterator
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $index = 0;

    /**
     * StringIterator constructor.
     * @param string $string
     */
    public function __construct($string = '')
    {
        $this->length = strlen($string);
        $this->string = $string;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->string[$this->index];
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->index < $this->length ? $this->index : null;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->index < $this->length;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->index = 0;
    }
}
