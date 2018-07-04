<?php

namespace ThrowExceptionNet\Compute;

class StringIterator implements \SeekableIterator, \Countable
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
    private $position = 0;

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
        return $this->string[$this->position];
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->position < $this->length ? $this->position : null;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->position < $this->length;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->length;
    }

    /**
     * @inheritdoc
     */
    public function seek($position)
    {
        $this->position = $position;
    }
}
