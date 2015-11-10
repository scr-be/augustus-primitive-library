<?php

/*
 * This file is part of the Scribe Augustus Primitive Library.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\Primitive\Tests\Set;

use PHPUnit_Framework_TestCase;
use Scribe\Primitive\Set\Set;

/**
 * Class SetTest.
 */
class SetTest extends PHPUnit_Framework_TestCase
{
    public function test_can_be_constructed_with_no_param()
    {
        $set = new Set();

        static::assertCount(0, $set);
    }

    public function test_can_be_constructed_with_param()
    {
        $set = new Set(['index1' => 'value1', 'index2' => 'value2']);

        static::assertCount(2, $set);
        static::assertTrue($set->contains('value1'));
        static::assertTrue($set->contains('value2'));
        static::assertFalse($set->containsKey('index1'));
        static::assertFalse($set->containsKey('index2'));
        static::assertTrue($set->containsKey(0));
        static::assertTrue($set->containsKey(1));
    }
}

/* EOF */
