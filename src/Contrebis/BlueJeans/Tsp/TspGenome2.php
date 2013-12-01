<?php

namespace Contrebis\BlueJeans\Tsp;


use Contrebis\BlueJeans\ListCollection;

class TspGenome2 extends TspGenome
{
    public function route()
    {
        $remaining = new ListCollection(range(0, count($this->cities()) - 1));
        $route = new ListCollection();

        // Doesn't matter where we start so optimise by fixing first city:
        $route->add($remaining->remove(0));
        $i = 0;
        if ($this->data instanceof ListCollection === false) xdebug_break();
        while ($remaining->count() > 0) {
            $bits = (int) ceil(log($route->count(), 2));
            $pos = bindec(implode('', $this->data->slice($i, $i + $bits)->toArray()));
            $route->insert(1 + $pos % $route->count(), $remaining->remove($remaining->count() - 1));
            $i += $bits;
        }
        return $route;
    }
}
