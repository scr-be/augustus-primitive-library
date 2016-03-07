<?php

/*
 * This file is part of the Augustus Primitive Library
 *
 * (c) Rob Frawley 2nd <rmf@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Collection;

use Closure;
use ArrayIterator;

/**
 * Class Collection.
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param array $elements
     */
    abstract public function __construct(array $elements = []);

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * @return int
     */
    public function count()
    {
        return (int) count($this->elements);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (bool) ($this->count() === 0);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * @param mixed $offset
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        return $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     *
     * @return $this
     */
    public function offsetUnset($offset)
    {
        return $this->removeKey($offset);
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
     * @return array
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element)
    {
        $this->elements[] = $element;

        return $this;
    }

    /**
     * @param array $collection
     *
     * @return $this
     */
    public function addAll(array $collection)
    {
        foreach ($collection as $key => $el) {
            $this->set($key, $el);
        }

        return $this;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function addAllCollection(CollectionInterface $collection)
    {
        $this->addAll($collection->toArray());

        return $this;
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
     * @param mixed $key
     */
    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @param mixed     $element
     * @param bool|true $strict
     *
     * @return bool
     */
    public function contains($element, $strict = true)
    {
        return (bool) (in_array($element, $this->elements, (bool) $strict));
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return (bool) (array_key_exists($key, $this->elements));
    }

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function remove($element)
    {
        if (null !== ($key = $this->indexOf($element))) {
            $this->removeKey($key);
        }

        return $this;
    }

    /**
     * @param mixed $key
     *
     * @return $this
     */
    public function removeKey($key)
    {
        if ($this->containsKey($key)) {
            unset($this->elements[$key]);
        }

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return bool
     */
    public function exists(Closure $predicate)
    {
        foreach ($this->elements as $key => $el) {
            if ($predicate($key, $el)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return $this
     */
    public function reverse()
    {
        $this->elements = array_reverse($this->elements, true);

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function filter(Closure $predicate)
    {
        $this->elements = array_filter($this->elements, $predicate);

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function sort(Closure $predicate)
    {
        uasort($this->elements, $predicate);

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return bool
     */
    public function forAll(Closure $predicate)
    {
        foreach ($this->elements as $key => $el) {
            if ($predicate($key, $el) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function map(Closure $predicate)
    {
        $this->elements = array_map($predicate, $this->elements);

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return CollectionInterface[]
     */
    public function partition(Closure $predicate)
    {
        $setOne = new static();
        $setTwo = new static();

        foreach ($this->elements as $key => $el) {
            if ($predicate($key, $el)) {
                $setOne->set($key, $el);
            } else {
                $setTwo->set($key, $el);
            }
        }

        return [$setOne, $setTwo];
    }

    /**
     * @param int      $offset
     * @param null|int $length
     *
     * @return array
     */
    public function slice($offset, $length = null)
    {
        return array_splice($this->elements, (int) $offset, $length, true);
    }

    /**
     * @param Closure $predicate
     *
     * @return null|mixed
     */
    public function find(Closure $predicate)
    {
        foreach ($this->elements as $el) {
            if ($predicate($el) === true) {
                return $el;
            }
        }

        return null;
    }

    /**
     * @param Closure $predicate
     *
     * @return null|mixed
     */
    public function findKey(Closure $predicate)
    {
        foreach ($this->getKeys() as $key) {
            if ($predicate($key) === true) {
                return $key;
            }
        }

        return null;
    }

    /**
     * @param mixed     $element
     * @param bool|true $strict
     *
     * @return mixed|null
     */
    public function indexOf($element, $strict = true)
    {
        if (false !== ($key = array_search($element, $this->elements, $strict))) {
            return $key;
        }

        return null;
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function merge(array $array)
    {
        $this->elements = array_merge($this->elements, $array);

        return $this;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function mergeCollection(CollectionInterface $collection)
    {
        $this->elements = array_merge($this->elements, $collection->toArray());

        return $this;
    }

    /**
     * @param int $int
     *
     * @return static
     */
    public function drop($int)
    {
        return new static(array_slice($this->elements, $int, null, true));
    }

    /**
     * @param int $int
     *
     * @return static
     */
    public function dropRight($int)
    {
        return new static(array_slice($this->elements, 0, -1 * $int, true));
    }

    /**
     * @param int $int
     *
     * @return static
     */
    public function take($int)
    {
        return new static(array_slice($this->elements, 0, $int, true));
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function union(CollectionInterface $collection)
    {
        $union = new static($this->elements);
        $union->mergeCollection($collection);

        return $union;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function intersect(CollectionInterface $collection)
    {
        $intersect = new static();

        foreach ($this->elements as $key => $el) {
            if ($collection->contains($el) && $collection->get($key) === $el) {
                $intersect->set($key, $el);
            }
        }

        return $intersect;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return static
     */
    public function symmetricDifference(CollectionInterface $collection)
    {
        $intersect = $this->intersect($collection);
        $difference = $this->union($collection);

        foreach ($intersect as $key => $el) {
            if ($difference->contains($el) && $difference->get($key) === $el) {
                $difference->remove($el);
            }
        }

        return $difference;
    }
}

/* EOF */
