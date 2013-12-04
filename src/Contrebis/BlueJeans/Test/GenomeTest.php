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
    }

    public function testCrossover()
    {
        $genome1 = $this->getAllZerosGenome();
        $genome2 = $this->getAllOnesGenome();

        $cross = $genome1->getCrossover($genome2, 8);
        $this->assertEquals('0000000011111111', $cross->data);
    }
}
