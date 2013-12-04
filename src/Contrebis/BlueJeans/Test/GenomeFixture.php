<?php

namespace Contrebis\BlueJeans\Test;


use Contrebis\BlueJeans\Genome;
use Contrebis\BlueJeans\GenomeFactory;

trait GenomeFixture
{
    public function getFactory()
    {
        return new GenomeFactory();
    }

    public function getAllZerosGenome()
    {
        return new Genome($this->getFactory(), str_pad('', 16, 0));
    }

    public function getAllOnesGenome()
    {
        return new Genome($this->getFactory(), str_pad('', 16, 1));
    }
}
