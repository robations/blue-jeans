<?php

namespace Contrebis\BlueJeans\Test;


use Contrebis\BlueJeans\Genome;

class NegativeGenome extends Genome
{
    public function fitness()
    {
        return parent::fitness() - 16;
    }
}
