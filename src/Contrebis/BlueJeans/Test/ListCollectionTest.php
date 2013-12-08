<?php

namespace Contrebis\BlueJeans\Test;


use Contrebis\BlueJeans\ListCollection;

class ListCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function getList($arr = [1, 2, 2, 3])
    {
        return new ListCollection($arr);
    }

    public function testInstantiation()
    {
        $ls = new ListCollection([1, 2, 3]);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\ListCollection', $ls);
    }

    public function testFirst()
    {
        $ls = $this->getList();
        $this->assertEquals(1, $ls->first());
    }

    public function testLast()
    {
        $ls = $this->getList();
        $this->assertEquals(3, $ls->last());
    }

    public function testKey()
    {
        $ls = $this->getList();
        $this->assertEquals(0, $ls->key());
    }

    public function testRemove()
    {
        $ls = $this->getList();
        $this->assertEquals(2, $ls->remove(0)->first());
        $this->assertEquals(1, $ls->remove(42)->first());
    }

    public function testRemoveElement()
    {
        $ls = $this->getList();
        $this->assertEquals([2, 2, 3], $ls->removeElement(1)->toArray());
        $this->assertEquals([1, 2, 2, 3], $ls->removeElement(-1)->toArray());
    }

    public function testOffsetSet()
    {
        $ls = $this->getList();
        $ls[0] = 99;
        $this->assertEquals([99, 2, 2, 3], $ls->removeElement(1)->toArray());
    }

    public function testOffsetUnset()
    {
        $ls = $this->getList();
        unset($ls[0]);
        $this->assertEquals([2, 2, 3], $ls->toArray());
    }

    public function testContainsKey()
    {
        $ls = $this->getList();
        $this->assertTrue($ls->containsKey(0));
        $this->assertFalse($ls->containsKey(-1));
    }

    public function testContains()
    {
        $ls = $this->getList();
        $this->assertTrue($ls->contains(2));
        $this->assertFalse($ls->contains(-1));
    }

    public function testExists()
    {
        $ls = $this->getList();
        $this->assertTrue($ls->exists(function ($x) {
            return $x === 2;
        }));
        $this->assertFalse($ls->exists(function ($x) {
            return $x === 4;
        }));
    }

    public function testForAll()
    {
        $ls = $this->getList();
        $this->assertTrue($ls->forAll(function ($x) {
            return $x > 0;
        }));
        $this->assertFalse($ls->forAll(function ($x) {
            return $x < 3;
        }));
    }

    public function testPartition()
    {
        $ls = $this->getList();
        $result = $ls->partition(function ($x) {
            return $x > 1;
        });

        $this->assertEquals([2, 2, 3], $result[0]->toArray());
        $this->assertEquals([1], $result[1]->toArray());
    }

    public function testIndexOf()
    {
        $ls = $this->getList();
        $this->assertEquals(1, $ls->indexOf(2));
        $this->assertEquals(null, $ls->indexOf(55));
    }

    public function testGet()
    {
        $ls = $this->getList();
        $this->assertEquals(1, $ls->get(0));
        $this->assertEquals(null, $ls->get(55));
    }

    public function testGetValues()
    {
        $ls = $this->getList();
        $this->assertEquals([1, 2, 2, 3], $ls->getValues());
    }

    public function testSet()
    {
        $ls = $this->getList();
        $ls->set(0, 99);
        $this->assertEquals([99, 2, 2, 3], $ls->getValues());
        $this->setExpectedException('\InvalidArgumentException');
        $ls->set(-1, 99);
    }

    public function testAdd()
    {
        $ls = $this->getList();
        $ls->add(99);
        $this->assertEquals([1, 2, 2, 3, 99], $ls->getValues());
    }

    public function testInsert()
    {
        $ls = $this->getList();
        $ls->insert(1, 99);
        $this->assertEquals([1, 99, 2, 2, 3], $ls->getValues());
    }

    public function testIsEmpty()
    {
        $ls1 = $this->getList();
        $ls2 = $this->getList([]);
        $this->assertEquals(false, $ls1->isEmpty());
        $this->assertEquals(true, $ls2->isEmpty());
    }

    public function testToString()
    {
        $ls = $this->getList();
        $this->assertEquals('[1, 2, 2, 3]', $ls->__toString());
    }

    public function testClear()
    {
        $ls = $this->getList();
        $this->assertEquals(true, $ls->clear()->isEmpty());
    }
}
