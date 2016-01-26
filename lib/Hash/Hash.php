<?php

/*
 * This file is part of the Augustus Primitive Library
 *
 * (c) Rob Frawley 2nd <rmf@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Hash;

use Scribe\Primitive\Collection\AbstractCollection;

/**
 * Class Hash.
 */
class Hash extends AbstractCollection implements HashInterface
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
