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

namespace SR\Primitive\Hash;

use SR\Primitive\Collection\AbstractCollection;

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
