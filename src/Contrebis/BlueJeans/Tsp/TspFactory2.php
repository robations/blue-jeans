<?php

namespace Contrebis\BlueJeans\Tsp;


use Contrebis\BlueJeans\GenomeFactory;

class TspFactory2 extends GenomeFactory
{
    public function __invoke($data = null)
    {
        return new TspGenome2($data);
    }
}
