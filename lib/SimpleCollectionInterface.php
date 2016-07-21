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
 * Interface SimpleCollectionInterface.
 */
interface SimpleCollectionInterface extends \Countable, \IteratorAggregate
{
    /**
     * @return mixed[]
     */
    public function toArray();

    /**
     * @return int
     */
    public function count();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return $this
     */
    public function clear();

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key);

    /**
     * @param mixed $key
     *
     * @return null|mixed
     */
    public function get($key);

    /**
     * @param mixed $key
     * @param mixed $element
     *
     * @return $this
     */
    public function set($key, $element);

    /**
     * @return \ArrayIterator
     */
    public function getIterator();
}

/* EOF */
