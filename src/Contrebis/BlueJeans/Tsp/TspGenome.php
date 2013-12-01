<?php

namespace Contrebis\BlueJeans\Tsp;


use Contrebis\BlueJeans\Genome;
use Contrebis\BlueJeans\ListCollection;
use Doctrine\Common\Collections\ArrayCollection;

class TspGenome extends Genome
{
    protected $_distances = [
        "London" => [
            "Aberdeen" => 537,
            "Birmingham" => 117,
            "Bristol" => 119,
            "Cambridge" => 60,
            "Cardiff" => 155,
            "Edinburgh" => 401,
            "Fishguard" => 260,
            "Glasgow" => 400,
            "Holyhead" => 264,
            "Liverpool" => 210,
            "Manchester" => 197,
            "Newcastle" => 276,
            "Oxford" => 56,
            "Penzance" => 312,
            "Stranraer" => 414,
            "Stratford" => 95,
        ],
        "Aberdeen" => [
            "Birmingham" => 430,
            "Bristol" => 511,
            "Cambridge" => 468,
            "Cardiff" => 532,
            "Edinburgh" => 127,
            "Fishguard" => 524,
            "Glasgow" => 149,
            "Holyhead" => 459,
            "Liverpool" => 361,
            "Manchester" => 354,
            "Newcastle" => 236,
            "Oxford" => 498,
            "Penzance" => 690,
            "Stranraer" => 244,
            "Stratford" => 461,
        ],
        "Birmingham" => [
            "Bristol" => 85,
            "Cambridge" => 101,
            "Cardiff" => 107,
            "Edinburgh" => 293,
            "Fishguard" => 177,
            "Glasgow" => 291,
            "Holyhead" => 155,
            "Liverpool" => 98,
            "Manchester" => 88,
            "Newcastle" => 198,
            "Oxford" => 63,
            "Penzance" => 278,
            "Stranraer" => 304,
            "Stratford" => 24,
        ],
        "Bristol" => [
            "Cambridge" => 178,
            "Cardiff" => 45,
            "Edinburgh" => 373,
            "Fishguard" => 120,
            "Glasgow" => 372,
            "Holyhead" => 232,
            "Liverpool" => 178,
            "Manchester" => 167,
            "Newcastle" => 291,
            "Oxford" => 74,
            "Penzance" => 195,
            "Stranraer" => 386,
            "Stratford" => 75,
        ],
        "Cambridge" => [
            "Cardiff" => 213,
            "Edinburgh" => 337,
            "Fishguard" => 319,
            "Glasgow" => 349,
            "Holyhead" => 246,
            "Liverpool" => 205,
            "Manchester" => 153,
            "Newcastle" => 228,
            "Oxford" => 80,
            "Penzance" => 368,
            "Stranraer" => 361,
            "Stratford" => 99,
        ],
        "Cardiff" => [
            "Edinburgh" => 401,
            "Fishguard" => 260,
            "Glasgow" => 400,
            "Holyhead" => 264,
            "Liverpool" => 210,
            "Manchester" => 197,
            "Newcastle" => 276,
            "Oxford" => 56,
            "Penzance" => 312,
            "Stranraer" => 414,
            "Stratford" => 95,
        ],
        "Edinburgh" => [
            "Fishguard" => 398,
            "Glasgow" => 45,
            "Holyhead" => 327,
            "Liverpool" => 225,
            "Manchester" => 218,
            "Newcastle" => 109,
            "Oxford" => 362,
            "Penzance" => 567,
            "Stranraer" => 133,
            "Stratford" => 324,
        ],
        "Fishguard" => [
            "Glasgow" => 398,
            "Holyhead" => 163,
            "Liverpool" => 167,
            "Manchester" => 189,
            "Newcastle" => 328,
            "Oxford" => 214,
            "Penzance" => 340,
            "Stranraer" => 411,
            "Stratford" => 194,
        ],
        "Glasgow" => [
            "Holyhead" => 321,
            "Liverpool" => 221,
            "Manchester" => 214,
            "Newcastle" => 150,
            "Oxford" => 355,
            "Penzance" => 563,
            "Stranraer" => 88,
            "Stratford" => 319,
        ],
        "Holyhead" => [
            "Liverpool" => 106,
            "Manchester" => 125,
            "Newcastle" => 250,
            "Oxford" => 212,
            "Penzance" => 409,
            "Stranraer" => 334,
            "Stratford" => 173,
        ],
        "Liverpool" => [
            "Manchester" => 34,
            "Newcastle" => 170,
            "Oxford" => 165,
            "Penzance" => 370,
            "Stranraer" => 236,
            "Stratford" => 124,
        ],
        "Manchester" => [
            "Newcastle" => 141,
            "Oxford" => 154,
            "Penzance" => 358,
            "Stranraer" => 226,
            "Stratford" => 116,
        ],
        "Newcastle" => [
            "Oxford" => 253,
            "Penzance" => 477,
            "Stranraer" => 164,
            "Stratford" => 226,
        ],
        "Oxford" => [
            "Penzance" => 265,
            "Stranraer" => 371,
            "Stratford" => 48,
        ],
        "Penzance" => [
            "Stranraer" => 576,
            "Stratford" => 264,
        ],
        "Stranraer" => [
            "Stratford" => 335,
        ],
        "Stratford" => [
        ],
    ];

    public function __construct(array $data = null, $elite = false)
    {
        if ($data === null) {
            $data = new ListCollection();
            for ($i = 0; $i < 16 * 4; $i++) {
                $data->add(mt_rand(0, 1));
            }
            $this->data = $data;
        } else {
            $this->data = new ListCollection($data);
        }
        $this->_distances = (new ArrayCollection($this->_distances))
            ->map(
                function ($el) {
                    return new ArrayCollection($el);
                }
            );
        $this->_elite = $elite;
    }

    public function lookupDistance($city1, $city2)
    {
        if ($this->_distances->containsKey($city1) and $this->_distances[$city1]->containsKey($city2)) {
            return $this->_distances[$city1][$city2];
        } elseif ($this->_distances->containsKey($city2) and $this->_distances[$city2]->containsKey($city1)) {
            return $this->_distances[$city2][$city1];
        } elseif ($city1 == $city2) {
            return 1000;
        }
        throw new \Exception(sprintf("Distance not found: %s to %s", $city1, $city2));
    }

    public function cities()
    {
        return $this->_distances->getKeys();
    }

    public function distance()
    {
        $distance = 0;
        $cities = $this->cities();
        $route = $this->route();
        for ($i = 0; $i < $route->count() - 1; $i++) {
            $distance += $this->lookupDistance($cities[$route[$i]], $cities[$route[$i + 1]]);
        }
        $distance += $this->lookupDistance($cities[$route[0]], $cities[$route->last()]);

        return $distance;
    }

    public function fitness()
    {
        return 50000 / $this->distance();
    }

    public function route()
    {
        $remaining = new ListCollection(range(0, count($this->cities()) - 1));
        $route = new ListCollection();

        // Doesn't matter where we start so optimise by fixing first city:
        $route->add($remaining->remove(0));
        $i = 0;
        while ($remaining->count() > 0) {
            $num = bindec(implode('', $this->data->slice($i, $i + 4)->toArray()));
            $route->add($remaining->remove($num % $remaining->count()));
            $i += 4;
        }

        return $route;
    }

    public function isValid()
    {
        return count(array_unique($this->route()->getValues())) === count($this->cities());
    }

    public function __toString()
    {
        $route = $this->route()->map(function ($x) {
            return $this->cities()[$x];
        })->toArray();

        return sprintf(
            "[%s] distance=%d\n%s",
            implode(", ", $route),
            $this->distance(),
            implode('', $this->data->toArray())
        );
    }
}
