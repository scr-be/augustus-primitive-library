<?php

/*
 * This file is part of the `src-run/augustus-primitive-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Primitive;

/**
 * Class SimpleCollection.
 */
class SimpleCollection implements SimpleCollectionInterface
{
    /**
     * @var mixed[]
     */
    protected $elements = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @return mixed[]
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        if (!array_key_exists($key, $this->elements)) {
            return null;
        }

        $unset = $this->elements[$key];
        unset($this->elements[$key]);

        return $unset;
    }

    /**
     * @param mixed $key
     *
     * @return null|mixed
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->elements)) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @param mixed $key
     * @param mixed $element
     *
     * @return $this
     */
    public function set($key, $element)
    {
        $this->elements[$key] = $element;

        return $this;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }
}

/* EOF */
