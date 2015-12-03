<?php

/*
 * This file is part of the Scribe Augustus Primitive Library.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Map;

use Scribe\Primitive\Collection\AbstractCollection;

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
