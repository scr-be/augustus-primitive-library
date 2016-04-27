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

namespace SR\Primitive\Tests;

use Faker;
use SR\Primitive\Collection;

/**
 * Class CollectionTest.
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Faker\Generator
     */
    private function getFaker()
    {
        static $faker;

        if (!$faker instanceof Faker\Factory) {
            $faker = Faker\Factory::create('en_EN');
        }

        return $faker;
    }

    /**
     * @param mixed[] $array
     *
     * @return Collection
     */
    private function getCollection(&$array, $size = 50)
    {
        $class = new Collection();
        $array = [];
        $faker = $this->getFaker();

        for ($i = 0; $i < $size; $i++) {
            $reset = ($i === 0 ? true : false);
            $k = $faker->unique($reset)->uuid;
            $v = $faker->unique($reset)->realText($faker->numberBetween($size*2, $size*3));
            $array[$k] = $v;
            $class->set($k, $v);
        }

        return $class;
    }

    public function testToArray()
    {
        $a = [];
        $c = $this->getCollection($a, 20);

        $this->assertSame($a, $c->toArray());
    }

    public function testSetAndRemoveAndCountAndClear()
    {
        $a = [];
        $c = $this->getCollection($a);
        $i = 0;

        $this->assertCount(50, $c);
        $this->assertFalse($c->isEmpty());

        foreach ($a as $k => $v) {
            ++$i;

            $this->assertTrue($c->contains($v));
            $this->assertTrue($c->containsKey($k));

            $this->assertSame($v, $c->get($k));

            $this->assertSame($v, $c->remove($k));
            $this->assertNull($c->remove($k));
            $this->assertFalse($c->contains($v));
            $this->assertFalse($c->containsKey($k));

            $this->assertNull($c->get($k));
            $this->assertCount(50-$i, $c);

            $c->set($k, $v);
            $this->assertTrue($c->contains($v));
            $this->assertTrue($c->containsKey($k));

            $this->assertSame($v, $c->remove($k));
            $this->assertFalse($c->contains($v));
            $this->assertFalse($c->containsKey($k));

            $c->add($v);
            $this->assertTrue($c->contains($v));
            $this->assertFalse($c->containsKey($k));

            $this->assertTrue($c->removeElement($v));
            $this->assertFalse($c->contains($v));
            $this->assertFalse($c->containsKey($k));

            $this->assertFalse($c->removeElement($v));
        }

        $this->assertTrue($c->isEmpty());

        foreach ($a as $k => $v) {
            $this->assertFalse($c->contains($v));
            $this->assertFalse($c->containsKey($k));
            $c->set($k, $v);
            $this->assertTrue($c->contains($v));
            $this->assertTrue($c->containsKey($k));
        }

        $this->assertFalse($c->isEmpty());
        $c->clear();
        $this->assertTrue($c->isEmpty());
    }

    public function testGetKeysAndGetValues()
    {
        $a = [];
        $c = $this->getCollection($a, 10);

        $this->assertSame(array_keys($a), $c->getKeys());
        $this->assertSame(array_values($a), $c->getValues());
    }

    public function testReverse()
    {
        $a = [];
        $c = $this->getCollection($a, 20);
        $a = array_reverse($a, true);

        $this->assertSame($a, $c->reverse()->toArray());
    }

    public function testSort()
    {
        $a = [];
        $c = $this->getCollection($a, 20);
        $_ = function ($a, $b) {
            return (int)substr($a, -2) > (int)substr($b, -2);
        };

        uasort($a, $_);

        $this->assertSame($a, $c->sort($_)->toArray());
    }

    public function testContainsElementCount()
    {
        $a = [];
        $c = $this->getCollection($a, 20);
        $_ = function ($a, $b) {
            return (int)substr($a, -2) > (int)substr($b, -2);
        };
        $c = $c->sort($_);

        foreach ($a as $i => $v) {
            $k = implode('', array_reverse(str_split($i, 1)));
            $c->set($k, $v);
        }

        $this->assertCount(40, $c);

        foreach ($a as $i => $v) {
            $this->assertSame(2, $c->containsElementCount($v));
        }
    }

    public function testClear()
    {
        $a = [];
        $c = $this->getCollection($a, 20);
        $_ = function ($a, $b) {
            return (int)substr($a, -2) > (int)substr($b, -2);
        };
        $c = $c->sort($_);

        foreach ($a as $i => $v) {
            $k = implode('', array_reverse(str_split($i, 1)));
            $c->set($k, $v);
        }

        $this->assertCount(40, $c);

        foreach ($a as $i => $v) {
            $this->assertSame(2, $c->containsElementCount($v));
        }
    }

    public function testPredicateMethods()
    {
        $a = [];
        $c = $this->getCollection($a, 40);
        $aa = $a;

        $return = $c->forAll(function ($v, $k) use (&$aa) {
            $ak = current($aa);
            $av = key($aa);
            array_shift($aa);

            return ($v === $av && $k === $ak);
        });

        $this->assertTrue($return);
        reset($a);

        $_ = function ($v, $k)  use ($a) {
            return ($v === key($a) && $k === current($a));
        };

        $this->assertFalse($c->forAll($_));
        $this->assertTrue($c->exists($_));
        $c->clear();
        $this->assertFalse($c->exists($_));

        $c = Collection::create($a);

        $c2 = $c->map(function ($v) {
            return implode('', array_reverse(str_split($v, 1)));
        });

        foreach ($c->toArray() as $k => $v) {
            $this->assertTrue($c2->containsKey($k));
            $this->assertFalse($c2->contains($v));
            $this->assertTrue($c2->contains(implode('', array_reverse(str_split($v, 1)))));
        }

        $cInt = $c2->filterKeys(function ($i) {
            $i = (int) substr($i, 0, 1);
            return (!empty($i) && is_int($i));
        });

        $cStr = $c2->filterKeys(function ($i) {
            $i = (int) substr($i, 0, 1);
            return (empty($i) || !is_int($i));
        });

        $this->assertLessThan(40, $cInt->count());
        $this->assertLessThan(40, $cStr->count());
        $this->assertGreaterThan(0, $cInt->count());
        $this->assertGreaterThan(0, $cStr->count());
        $this->assertSame(40, $cInt->count() + $cStr->count());

        foreach ($cInt->toArray() as $i => $v) {
            $this->assertFalse($cStr->containsKey($i));
        }

        foreach ($cStr->toArray() as $i => $v) {
            $this->assertFalse($cInt->containsKey($i));
        }

        $cc = $cInt->merge($cStr);

        $this->assertCount($c->count(), $cc);

        $_ = function ($a, $b) {
            return $a > $b;
        };

        $this->assertNotSame($c2->toArray(), $cc->toArray());
        $c1 = $cc->sortKeys($_);
        $c2 = $c2->sortKeys($_);
        $this->assertSame($c2->toArray(), $c1->toArray());

        $cc1 = $c1->shuffle();
        $cc2 = $c2->shuffle();

        $this->assertNotSame($cc1->toArray(), $cc2->toArray());
        $this->assertSame(40, $cc1->count());
        $this->assertSame(40, $cc2->count());

        foreach ($cc1->toArray() as $i => $v) {
            $this->assertTrue($cc2->contains($v));
            $this->assertTrue($cc2->containsKey($i));
        }

        foreach ($cc2->toArray() as $i => $v) {
            $this->assertTrue($cc1->contains($v));
            $this->assertTrue($cc1->containsKey($i));
        }

        $this->assertNotSame($cc1->toArray(), $cc2->toArray());
        $this->assertTrue($cc1->same($cc2));
        $this->assertTrue($cc2->same($cc1));

        $cc1 = $cc1->sortKeys($_);
        $cc2 = $cc2->sortKeys($_);

        $this->assertSame($cc1->toArray(), $cc2->toArray());
        $this->assertTrue($cc1->same($cc2));
        $this->assertTrue($cc2->same($cc1));

        $this->assertSame(40, $cc1->count());
        $this->assertSame(40, $cc2->count());

        $cc1 = $cc1->filterBoth(function ($v, $i) {
            return 0 === preg_match('{[^a-zA-Z0-9\s.!,\']}', $v);
        });

        $cc2 = $cc2->filterBoth(function ($v, $i) {
            return 0 !== preg_match('{[^a-zA-Z0-9\s.!,\']}', $v);
        });

        $this->assertNotSame($cc1->toArray(), $cc2->toArray());

        $cc2p1 = Collection::create($cc2->toArray());
        $cc2p2 = $cc2p1->slice(2, 4);
        $cc2p3 = $cc2p1->slice(12, 3);

        foreach (array_merge($cc2p1->toArray(), $cc2p2->toArray(), $cc2p3->toArray()) as $i => $v) {
            $this->assertTrue($cc2->contains($v));
            $this->assertTrue($cc2->containsKey($i));
        }

        $this->assertFalse($cc2->same($cc2p2));
        $this->assertFalse($cc2->same($cc2p3));

        $cc2p = $cc2p1->merge($cc2p2, $cc2p3);

        $this->assertTrue($cc2->same($cc2p));
        $this->assertSame($cc2->toArray(), $cc2p->toArray());

        list($cc2p4, $cc2p5) = $cc2->partition(function ($v, $i) {
            static $i = 0;
            ++$i;
            return ($i % 2) === 0;
        });

        foreach (array_merge($cc2p4->toArray(), $cc2p5->toArray()) as $i => $v) {
            $this->assertTrue($cc2->contains($v));
            $this->assertTrue($cc2->containsKey($i));
        }

        $this->assertFalse($cc2->same($cc2p4));
        $this->assertFalse($cc2->same($cc2p5));

        $cc2p = $cc2p4->merge($cc2p5);

        $this->assertTrue($cc2->same($cc2p));
        $this->assertSame($cc2->sortKeys($_)->toArray(), $cc2p->sortKeys($_)->toArray());
    }

    public function testToString()
    {
        $c = $this->getCollection($a, 5);

        $this->assertRegExp('{.*\Collection@[0-9a-z]+}', $c->__toString());
    }

    public function testArrayAccess()
    {
        $a = [];
        $c = $this->getCollection($a, 5);

        foreach ($a as $i => $v) {
            $this->assertTrue(isset($c[$i]));
            $this->assertSame($v, $c[$i]);
        }

        $c->clear();
        $a = array_reverse($a, true);

        foreach ($a as $i => $v) {
            $this->assertFalse(isset($c[$i]));
            $this->assertNotSame($v, $c[$i]);

            $c[$i] = $v;
            $this->assertSame($v, $c[$i]);
            $this->assertTrue(isset($c[$i]));

            unset($c[$i]);
            $this->assertFalse(isset($c[$i]));
            $this->assertNotSame($v, $c[$i]);

            $c[$i] = $v;
            $this->assertSame($v, $c[$i]);
        }

        $c[] = 'a-value-without-a-specified-key!';

        $this->assertTrue($c->contains('a-value-without-a-specified-key!'));
    }

    public function testIteratorAggregate()
    {
        $a = [];
        $c = $this->getCollection($a, 5);

        for ($n = 0; $n < 2; ++$n) {
            $i = 0;
            reset($a);
            foreach ($c as $key => $val) {
                $this->assertEquals(key($a), $key);
                $this->assertEquals(current($a), $val);
                next($a);
                ++$i;
            }
            $this->assertEquals(5, $i);
        }
    }
}

/* EOF */
