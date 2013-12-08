<?php

namespace Contrebis\BlueJeans\Test;

use Contrebis\BlueJeans\Genome;

class GenomeTest extends \PHPUnit_Framework_TestCase
{
    use GenomeFixture;

    public function testInstatiation()
    {
        $genome1 = new Genome($this->getFactory());
        $this->assertInstanceOf('Contrebis\BlueJeans\Genome', $genome1);

        $genome2 = $this->getAllZerosGenome();
        $this->assertEquals(str_pad('', 16, '0'), $genome2->data);

        $genome3 = new Genome($this->getFactory(), str_pad('', 16, '0'), true);
        $this->assertEquals(true, $genome3->isElite());
    }

    public function testMutation()
    {
        $genome = $this->getAllZerosGenome();

        $mutated = $genome->getMutated(1);
        $this->assertEquals(str_pad('', 16, '1'), $mutated->data);

        $genome->setElite(true);
        $genome2 = $genome->getMutated(1);
        $this->assertTrue($genome2->eq($genome));
    }

    public function testCrossover()
    {
        $genome1 = $this->getAllZerosGenome();
        $genome2 = $this->getAllOnesGenome();

        $cross = $genome1->getCrossover($genome2, 8);
        $this->assertEquals('0000000011111111', $cross->data);

        $genome1->setElite(true);
        $genome3 = $genome1->getCrossover($genome2);
        $this->assertTrue($genome3->eq($genome1));
    }

    public function testHash()
    {
        $genome1 = $this->getAllZerosGenome();
        $genome2 = $this->getAllZerosGenome();

        $this->assertEquals($genome1->hash(), $genome2->hash());
    }

    public function testEquality()
    {
        $genome1 = $this->getAllZerosGenome();
        $genome2 = $this->getAllZerosGenome();
        $genome3 = $this->getAllOnesGenome();

        $this->assertTrue($genome1->eq($genome2));
        $this->assertFalse($genome2->eq($genome3));
        $this->assertFalse($genome3->eq($genome1));
    }
}
