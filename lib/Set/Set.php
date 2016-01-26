<?php

/*
 * This file is part of the Augustus Primitive Library
 *
 * (c) Rob Frawley 2nd <rmf@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Set;

use Closure;
use Scribe\Primitive\Collection\AbstractCollection;
use Scribe\Primitive\Collection\CollectionInterface;

/**
 * Class Set.
 */
class Set extends AbstractCollection implements SetInterface
{
    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->addAll($elements);
    }

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element)
    {
        if (!$this->contains($element)) {
            $this->elements[] = $element;
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $element
     *
     * @return $this
     */
    public function set($key, $element)
    {
        parent::set($key, $element);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function filter(Closure $predicate)
    {
        parent::filter($predicate);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function sort(Closure $predicate)
    {
        parent::sort($predicate);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @param Closure $predicate
     *
     * @return $this
     */
    public function map(Closure $predicate)
    {
        parent::map($predicate);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function merge(array $array)
    {
        parent::merge($array);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @param CollectionInterface $collection
     *
     * @return $this
     */
    public function mergeCollection(CollectionInterface $collection)
    {
        parent::mergeCollection($collection);

        $this->normalizeElements();

        return $this;
    }

    /**
     * @return $this
     */
    private function normalizeElements()
    {
        $this->elements = array_unique(array_values($this->elements));

        return $this;
    }
}

/* EOF */
