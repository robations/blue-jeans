<?php

namespace Contrebis\BlueJeans;


class GenomeFactory
{
    function __invoke($data = null)
    {
        return new Genome($this, $data);
    }
}
