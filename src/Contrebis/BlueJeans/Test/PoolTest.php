<?php

namespace Contrebis\BlueJeans\Test;


use Contrebis\BlueJeans\GenomeFactory;
use Contrebis\BlueJeans\Pool;

class PoolTest extends \PHPUnit_Framework_TestCase
{
    private function getGenomeFactory()
    {
        return new GenomeFactory();
    }

    private function getPool()
    {
        return new Pool(10, $this->getGenomeFactory());
    }

    public function testInstantiation()
    {
        $factory = $this->getGenomeFactory();
        $pool = new Pool(2, $factory);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool);

        $pool2 = new Pool($pool->_pool, $factory);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool2);

        $pool3 = new Pool(2);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool3);

        $pool4 = new Pool($pool3);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool4);

        $pool5 = new Pool($pool4->_pool->toArray());
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool5);
    }

    public function testToString()
    {
        $pool = $this->getPool();
        $this->assertRegExp('/\[\s*([01]+,\s*){9}[01]+\s*\]/', $pool->__toString());
    }

    public function testSelectionPool()
    {
        $pool1 = $this->getPool();
        $pool2 = $pool1->getSelectionPool(2, 2);
        $this->assertInstanceOf('Contrebis\\BlueJeans\\Pool', $pool2);
        $this->assertEquals($pool1->_pool->count(), $pool2->_pool->count());
    }

    public function testMutate()
    {
        $pool = $this->getPool();
        $poolSize = $pool->_pool->count();
        $pool->mutate(1);
        $this->assertEquals($poolSize, $pool->_pool->count());
    }

    public function testCrossover()
    {
        $pool = $this->getPool();
        $poolSize = $pool->_pool->count();
        $pool->crossover(0.5);
        $this->assertEquals($poolSize, $pool->_pool->count());
        $pool->crossover(0);
        $this->assertEquals($poolSize, $pool->_pool->count());
    }

    public function testTotalFitness()
    {
        $pool = $this->getPool();
        $this->assertGreaterThan($pool->getMaxFitness(), $pool->getTotalFitness());
    }

    public function testMaxFitness()
    {
        $pool = $this->getPool();
        $this->assertGreaterThan($pool->getMinFitness(), $pool->getMaxFitness());
    }

    public function testMeanFitness()
    {
        $pool = $this->getPool();
        $this->assertLessThanOrEqual($pool->getMaxFitness(), $pool->getMeanFitness());
        $this->assertGreaterThanOrEqual($pool->getMinFitness(), $pool->getMeanFitness());
    }

    public function testFittestGenomes()
    {
        $pool = $this->getPool();
        $this->assertEquals($pool->getFittestGenomes(1)[0]->fitness(), $pool->getMaxFitness());
        $this->assertEquals($pool->getFittestGenomes(1, true)[0]->fitness(), $pool->getMaxFitness(true));
    }

    public function testDiversity()
    {
        $pool = $this->getPool();
        $this->assertGreaterThan(0, $pool->getDiversity());
    }
}
