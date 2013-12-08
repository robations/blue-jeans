<?php

namespace Contrebis\BlueJeans\Test;


use Contrebis\BlueJeans\GenomeFactory;

class NegativeGenomeFactory extends GenomeFactory
{
    public function __invoke($data = null)
    {
        return new NegativeGenome($this, $data);
    }
}
