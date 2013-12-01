<?php

namespace Contrebis\BlueJeans\Tsp;


class TspFactory
{
    public function __invoke($data = null)
    {
        return new TspGenome($data);
    }
}
