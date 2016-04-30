<?php

/*
 * This file is part of the `src-run/augustus-primitive-library` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\Primitive;

/**
 * Class CollectionInterface.
 */
interface CollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @param mixed[] $elements
     *
     * @return CollectionInterface
     */
    public static function create(array $elements = []);

    /**
     * @return mixed[]
     */
    public function toArray();

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key);

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function removeElement($element);

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset);

    /**
     * @param mixed $offset
     *
     * @return null|mixed
     */
    public function offsetGet($offset);

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     */
    public function offsetSet($offset, $value);

    /**
     * @param mixed $offset
     *
     * @return $this
     */
    public function offsetUnset($offset);

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element);

    /**
     * @param CollectionInterface[] $collections
     *
     * @return bool
     */
    public function equitable(CollectionInterface ...$collections);

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function exists(\Closure $predicate);

    /**
     * @param mixed $element
     *
     * @return null|mixed
     */
    public function indexOf($element);

    /**
     * @param mixed $key
     *
     * @return null|mixed
     */
    public function get($key);

    /**
     * @return mixed[]
     */
    public function getKeys();

    /**
     * @return mixed[]
     */
    public function getValues();

    /**
     * @return int
     */
    public function count();

    /**
     * @param mixed $search
     *
     * @return int
     */
    public function instancesOf($search);

    /**
     * @param mixed $key
     * @param mixed $element
     *
     * @return $this
     */
    public function set($key, $element);

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     * @param \Closure $closure
     *
     * @return CollectionInterface
     */
    public function map(\Closure $closure);

    /**
     * @param \Closure $predicate
     * @param int      $flag
     *
     * @return CollectionInterface
     */
    public function filter(\Closure $predicate, $flag = 0);

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function filterByKeys(\Closure $predicate);

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function forAll(\Closure $predicate);

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface[]
     */
    public function partition(\Closure $predicate);

    /**
     * @return $this
     */
    public function clear();

    /**
     * @param int      $offset
     * @param null|int $length
     *
     * @return CollectionInterface
     */
    public function slice($offset, $length = null);

    /**
     * @param CollectionInterface[] $collections
     *
     * @return CollectionInterface
     */
    public function merge(CollectionInterface ...$collections);

    /**
     * @return CollectionInterface
     */
    public function reverse();

    /**
     * @return CollectionInterface
     */
    public function shuffle();

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function sort(\Closure $predicate);

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function sortByKeys(\Closure $predicate);
}
