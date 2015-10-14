<?php

namespace Scribe\PrimitiveAugustus\Map;

use Scribe\PrimitiveAugustus\Collection\AbstractCollection;

/**
 * Class Map.
 */
class Map extends AbstractCollection implements MapInterface
{
    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }
}

/* EOF */
