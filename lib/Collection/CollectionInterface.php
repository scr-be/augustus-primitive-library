<?php

/*
 * This file is part of the Scribe Augustus Primitive Library.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Collection;

use Closure;
use Countable;
use IteratorAggregate;
use ArrayAccess;
use ArrayIterator;

/**
 * Interface CollectionInterface.
 */
interface CollectionInterface extends Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return ArrayIterator
     */
    public function getIterator();

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @return mixed
     */
    public function key();

    /**
     * @return mixed
     */
    public function next();

    /**
     * @return mixed
     */
    public function current();

    /**
     * @return int
     */
    public function count();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset);

    /**
     * @param mixed $offset
     *
     * @return null
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
     * @return $this
     */
    public function clear();

    /**
     * @return array
     */
    public function getValues();

    /**
     * @return array
     */
    public function getKeys();

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element);

    /**
     * @param array $collection
     *
     * @return $this
     */
    public function addAll(array $collection);

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function addAllCollection(CollectionInterface $collection);

    /**
     * @param mixed $key
     * @param mixed $element
     *
     * @return $this
     */
    public function set($key, $element);

    /**
     * @param mixed $key
     *
     * @return null
     */
    public function get($key);

    /**
     * @param mixed     $element
     * @param bool|true $strict
     *
     * @return bool
     */
    public function contains($element, $strict = true);

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function remove($element);

    /**
     * @param mixed $key
     *
     * @return $this
     */
    public function removeKey($key);

    /**
     * @param Closure $predicate
     *
     * @return bool
     */
    public function exists(Closure $predicate);

    /**
     * @return $this
     */
    public function reverse();

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function filter(Closure $predicate);

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function sort(Closure $predicate);

    /**
     * @param Closure $predicate
     *
     * @return bool
     */
    public function forAll(Closure $predicate);

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function map(Closure $predicate);

    /**
     * @param Closure $predicate
     *
     * @return CollectionInterface[]
     */
    public function partition(Closure $predicate);

    /**
     * @param int      $offset
     * @param null|int $length
     *
     * @return array
     */
    public function slice($offset, $length = null);

    /**
     * @param Closure $predicate
     *
     * @return null|mixed
     */
    public function find(Closure $predicate);

    /**
     * @param Closure $predicate
     *
     * @return null|mixed
     */
    public function findKey(Closure $predicate);

    /**
     * @param mixed     $element
     * @param bool|true $strict
     *
     * @return mixed|null
     */
    public function indexOf($element, $strict = true);

    /**
     * @param array $array
     *
     * @return $this
     */
    public function merge(array $array);

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function mergeCollection(CollectionInterface $collection);

    /**
     * @param int $int
     *
     * @return static
     */
    public function drop($int);

    /**
     * @param int $int
     *
     * @return static
     */
    public function dropRight($int);

    /**
     * @param int $int
     *
     * @return static
     */
    public function take($int);

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function union(CollectionInterface $collection);

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function intersect(CollectionInterface $collection);

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function symmetricDifference(CollectionInterface $collection);
}

/* EOF */
