<?php

namespace Scribe\Primitive\Tests\Map;

use PHPUnit_Framework_TestCase;
use Scribe\Primitive\Map\Map;

/**
 * Class MapTest.
 */
class MapTest extends PHPUnit_Framework_TestCase
{
    public function test_can_be_constructed_with_no_param()
    {
        $map = new Map();

        static::assertCount(0, $map);
    }

    public function test_can_be_constructed_with_param()
    {
        $map = new Map(['index1' => 'value1', 'index2' => 'value2']);

        static::assertCount(2, $map);
        static::assertTrue($map->contains('value1'));
        static::assertTrue($map->contains('value2'));
        static::assertTrue($map->containsKey('index1'));
        static::assertTrue($map->containsKey('index2'));
    }

    public function test_can_return_array()
    {
        $array = ['index1' => 'value1', 'index2' => 'value2'];
        $map = new Map($array);

        static::assertEquals($array, $map->toArray());
    }

    public function test_support_iterator_and_array_access()
    {
        $array = ['index1' => 'value1', 'index2' => 'value2'];
        $map = new Map($array);

        foreach ($map as $index => $value) {
            static::assertArrayHasKey($index, $array);
            static::assertTrue(in_array($value, $array, true));
        }

        foreach ($array as $index => $value) {
            static::assertTrue($map[$index] === $value);
        }

        $newArray = ['index1' => 'value1b', 'index2' => 'value2b'];

        foreach ($newArray as $index => $value) {
            $map[$index] = $value;
        }

        foreach ($newArray as $index => $value) {
            static::assertTrue($map[$index] === $value);
            static::assertFalse($map[$index] === $array[$index]);
        }

        $nonIndexedArray = ['value1c', 'value2c'];

        foreach ($nonIndexedArray as $value) {
            $map[] = $value;
        }

        foreach (range(0, 1) as $index) {
            static::assertTrue($map->containsKey($index));
            static::assertTrue(isset($map[$index]));
            static::assertFalse(empty($map[$index]));
            static::assertFalse($map->isEmpty());
            unset($map[$index]);
            static::assertFalse($map->containsKey($index));
            static::assertFalse(isset($map[$index]));
            static::assertTrue(empty($map[$index]));
        }

        $map->clear();

        static::assertTrue($map->isEmpty());

        $array = ['i1' => 'v1', 'i2' => 'v2', 'i3' => 'v3'];

        $map = new Map($array);

        static::assertEquals($array['i1'], $map->first());
        static::assertEquals(array_keys($array)[0], $map->key());
        static::assertEquals(array_values($array)[0], $map->current());
        static::assertEquals(array_values($array)[1], $map->next());
        static::assertEquals($array['i3'], $map->last());
        static::assertEquals(array_keys($array)[2], $map->key());
        static::assertEquals(array_values($array)[2], $map->current());
        static::assertEquals(array_keys($array), $map->getKeys());

        static::assertEquals(array_keys($array), $map->getKeys());
        static::assertEquals(array_values($array), $map->getValues());
    }

    public function test_add_collection()
    {
        $a1 = ['i1' => 'v1', 'i2' => 'v2'];
        $a2 = ['i1' => 'v1', 'i3' => 'v3', 'i5' => 'v5', 'i5' => 'v5'];
        $r = ['i1' => 'v1', 'i2' => 'v2', 'i3' => 'v3', 'i5' => 'v5', 'i5' => 'v5'];

        $map = new Map($a1);

        static::assertEquals($a1, $map->toArray());

        $map->addAllCollection(new Map($a2));

        static::assertEquals($r, $map->toArray());
    }

    public function test_get_unset_key()
    {
        $map = new Map([1, 2, 3, 4]);

        static::assertNull($map->get('some-unknown-key'));
    }

    public function test_removal()
    {
        $a = [10, 20, 9 => 30];

        $map = new Map([10, 20, 9 => 30]);

        static::assertEquals($a, $map->toArray());
        $map->remove(30);
        static::assertNotEmpty($a, $map->toArray());
    }

    public function test_closure_predicates()
    {
        $map = new Map(['some-string', 'explicit-index' => 1000, 80]);

        static::assertTrue($map->exists(function($i, $v) {
            if ($v === 80) { return true; }
        }));

        static::assertFalse($map->exists(function($i, $v) {
            return false;
        }));

        $map->reverse();

        static::assertEquals([1 => 80, 'explicit-index' => 1000, 0 => 'some-string'], $map->toArray());

        $mapFiltered = clone $map;

        $mapFiltered->filter(function($v) {
            if (is_int($v)) { return false; }
            return true;
        });

        static::assertCount(1, $mapFiltered);
        static::assertEquals([0 => 'some-string'], $mapFiltered->toArray());

        $mapSort = clone $map;

        $mapSort->sort(function($i, $v) {
            if (is_string($v)) { return true; } else { return false; }
        });

        static::assertEquals([0 => 'some-string', 'explicit-index' => 1000, 1 => 80], $mapSort->toArray());
    }
}

/* EOF */
