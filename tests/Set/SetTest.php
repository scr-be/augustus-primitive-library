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

    public function test_can_return_array()
    {
        $array = ['index1' => 'value1', 'index2' => 'value2'];
        $set = new Set($array);

        static::assertEquals(array_values($array), $set->toArray());
    }

    public function test_can_add()
    {
        $array = ['value1', 'value2'];
        $set = new Set($array);
        $set->add('value1');
        $set->add('value3');

        static::assertEquals(['value1', 'value2', 'value3'], $set->toArray());
    }

    public function test_can_merge_arrays()
    {
        $set1 = new Set(['index1' => 'value1', 'index2' => 'value2']);
        $set2 = ['index4' => 'value1', 'index2' => 'value2'];
        $set3 = ['abcdef', 'index2' => 'idk'];
        $set1->merge($set2);
        $set1->merge($set3);

        static::assertEquals(['value1', 'value2', 'abcdef', 'idk'], $set1->toArray());
    }

    public function test_can_merge_collection()
    {
        $set1 = new Set(['index1' => 'value1', 'index2' => 'value2']);
        $set2 = new Set(['index4' => 'value1', 'index2' => 'value2']);
        $set3 = new Set(['abcdef', 'index2' => 'idk']);
        $set1->mergeCollection($set2);
        $set1->mergeCollection($set3);

        static::assertEquals(['value1', 'value2', 'abcdef', 'idk'], $set1->toArray());
    }
}

/* EOF */
