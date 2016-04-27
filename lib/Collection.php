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
 * Class Collection.
 */
class Collection implements CollectionInterface
{
    /**
     * @var mixed[]
     */
    private $elements = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @param array $elements
     *
     * @return static
     */
    public static function create(array $elements = [])
    {
        return new static($elements);
    }

    /**
     * @return array
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
        if (!$this->containsKey($key)) {
            return null;
        }

        $unset = $this->elements[$key];
        unset($this->elements[$key]);

        return $unset;
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function removeElement($element)
    {
        if (null !== ($key = $this->indexOf($element))) {
            $this->remove($key);

            return true;
        }

        return false;
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
     *
     * @return array|null
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
        return $this->remove($offset);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param CollectionInterface[] $collections
     *
     * @return bool
     */
    public function same(CollectionInterface ...$collections)
    {
        $_ = function ($a, $b) {
            return $a > $b;
        };

        $masterAssertion = $this->sortKeys($_);

        $passedAssertion = array_filter($collections, function (CollectionInterface $v) use ($_, $masterAssertion) {
            $assertion = $v->sortKeys($_);
            return $assertion->toArray() === $masterAssertion->toArray();
        });

        return count($collections) === count($passedAssertion);
    }

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function exists(\Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $element
     *
     * @return mixed|null
     */
    public function indexOf($element)
    {
        if (false !== ($key = array_search($element, $this->elements, true))) {
            return $key;
        }

        return null;
    }

    /**
     * @param mixed $key
     *
     * @return array|null
     */
    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param mixed $search
     *
     * @return int
     */
    public function containsElementCount($search)
    {
        $elements = $this->elements;
        $elements = array_filter($elements, function ($v) use ($search) {
            return $v === $search;
        });

        return count($elements);
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
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param \Closure $closure
     *
     * @return CollectionInterface
     */
    public function map(\Closure $closure)
    {
        return static::create(array_map($closure, $this->elements));
    }

    /**
     * @param \Closure $predicate
     * @param int      $flag
     *
     * @return CollectionInterface
     */
    public function filter(\Closure $predicate, $flag = 0)
    {
        $elements = $this->elements;

        return static::create(array_filter($elements, $predicate, $flag));
    }

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function filterKeys(\Closure $predicate)
    {
        return $this->filter($predicate, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function filterBoth(\Closure $predicate)
    {
        return $this->filter($predicate, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function forAll(\Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if (!$predicate($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface[]
     */
    public function partition(\Closure $predicate)
    {
        $a = $b = [];

        foreach ($this->elements as $key => $element) {
            if ($predicate($element, $key)) {
                $a[$key] = $element;
            } else {
                $b[$key] = $element;
            }
        }

        return [static::create($a), static::create($b)];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s@%s', __CLASS__, spl_object_hash($this));
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
     * @param int      $offset
     * @param null|int $length
     *
     * @return CollectionInterface
     */
    public function slice($offset, $length = null)
    {
        $elements = $this->elements;

        return static::create(array_splice($elements, $offset, $length));
    }

    /**
     * @param CollectionInterface[] $collections
     *
     * @return CollectionInterface
     */
    public function merge(CollectionInterface ...$collections)
    {
        $mergedElements = [];
        array_unshift($collections, $this);

        foreach ($collections as $c) {
            $array = $c->toArray();

            array_walk($array, function ($v, $i) use (&$mergedElements) {
                $mergedElements[$i] = $v;
            });
        }

        return static::create($mergedElements);
    }

    /**
     * @return CollectionInterface
     */
    public function reverse()
    {
        return static::create(array_reverse($this->elements, true));
    }

    /**
     * @return CollectionInterface
     */
    public function shuffle()
    {
        $collection = [];
        $randomKeys = array_keys($this->elements);

        shuffle($randomKeys);

        foreach ($randomKeys as $key) {
            $collection[$key] = $this->elements[$key];
        }

        return static::create($collection);
    }

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function sort(\Closure $predicate)
    {
        $elements = $this->elements;
        uasort($elements, $predicate);

        return static::create($elements);
    }

    /**
     * @param \Closure $predicate
     *
     * @return CollectionInterface
     */
    public function sortKeys(\Closure $predicate)
    {
        $elements = $this->elements;
        uksort($elements, $predicate);

        return static::create($elements);
    }
}

/* EOF */
