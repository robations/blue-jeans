<?php

namespace Contrebis\BlueJeans;


use Doctrine\Common\Collections\ArrayCollection;

class Pool
{

    /**
     * @var ListCollection
     */
    public $_pool;

    public function __construct($arg1, $genomeFactory = null)
    {
        if ($genomeFactory === null) {
            $genomeFactory = new GenomeFactory();
        }
        $this->_genomeFactory = $genomeFactory;

        $this->_pool = new ListCollection();
        if ($arg1 instanceof Pool) {
            $this->_pool = $arg1->getPopulation();
            $this->_genomeFactory = $arg1->_genomeFactory;
        } elseif ($arg1 instanceof ListCollection) {
            $this->_pool = $arg1;
        } elseif (is_array($arg1)) {
            $this->_pool = new ListCollection($arg1);
        } elseif (is_int($arg1)) {
            for ($i = 0; $i < $arg1; $i++) {
                $this->_pool[] = $genomeFactory();
            }
        }
    }

    public function __toString()
    {
        $mapStr = function ($item) {
            return $item->__toString();
        };

        return sprintf("[\n%s\n]", implode(",\n", $this->_pool->map($mapStr)->toArray()));
    }

    public function getSelectionPool($elitism = 0, $freshBlood = 0)
    {
        $poolSize = $this->_pool->count();
        $newPool = $this->getFittestGenomes($elitism)->map(
            function ($x) {
                $y = clone $x;
                $y->setElite(true);

                return $y;
            }
        );

        for ($i = 0; $i < $freshBlood; $i++) {
            $newPool[] = $this->_genomeFactory->__invoke();
        }

        $totalFitness = 0;
        $cumul = array();
        $cumulKeys = array();
        /* @var $x Genome */
        foreach ($this->_pool as $x) {
            $totalFitness = $totalFitness + $x->fitness();
            $x->setElite(false);
            $cumul[] = $x;
            $cumulKeys[] = $totalFitness;
        }

        for ($i = 0; $i < $poolSize - $elitism - $freshBlood; $i++) {
            $rand = $totalFitness * lcg_value();
            for ($j = 0; $j < count($cumulKeys); $j++) {
                if ($cumulKeys[$j] >= $rand) {
                    $newPool[] = $cumul[$j];
                    break;
                }
            }
        }
        $shuffled = $newPool->toArray();
        shuffle($shuffled);
        $newPool = new ListCollection($shuffled);

        return new Pool($newPool, $this->_genomeFactory);
    }

    public function mutate($mutationProb)
    {
        $this->_pool = $this->_pool->map(function (Genome $x) use ($mutationProb) {
            return $x->getMutated($mutationProb);
        });
    }

    public function crossover($crossoverProb)
    {
        $newPool = new ListCollection();
        for ($i = 0; $i < $this->_pool->count() - 1; $i += 2) {
            /** @var $a Genome */
            /** @var $b Genome */
            $a = $this->_pool[$i];
            $b = $this->_pool[$i + 1];
            if (lcg_value() < $crossoverProb) {
                $newPool[] = $a->getCrossover($b);
                $newPool[] = $b->getCrossover($a);
            } else {
                $newPool[] = $a;
                $newPool[] = $b;
            }
        }
        $this->_pool = $newPool;
    }

    public function getTotalFitness($valid = false)
    {
        $totalFitness = 0;
        foreach ($this->_pool as $x) {
            if (!$valid || $x->isValid()) {
                $totalFitness = $totalFitness + $x->fitness();
            }
        }
        return $totalFitness;
    }

    public function getMaxFitness($valid = false)
    {
        return $this->_pool->filter(function (Genome $x) { return $x->isValid(); })
            ->reduce(function ($initial, Genome $el) { return max($initial, $el->fitness()); }, null);
    }

    public function getMinFitness()
    {
        return $this->_pool->
            reduce(
                function ($initial, Genome $el) {
                    return min($el->fitness(), $initial);
                },
                true
            );
    }

    public function getMeanFitness($valid = false)
    {
        return $this->getTotalFitness($valid) / $this->_pool->count();
    }

    /**
     * @param int $num Number to return
     * @param bool $valid If true, only return genomes with a valid solution
     * @return ListCollection
     */
    public function getFittestGenomes($num = 1, $valid = false)
    {
        $genomes = $valid ? $this->getPopulation()
                ->filter(function ($x) {
                    return $x->isValid();
                }) : $this->getPopulation();
        $fittest = $genomes->sort(
            function (Genome $a, Genome $b) {
                return -$a->cmp($b);
            }
        );

        return $fittest->slice(0, $num);
    }

    public function getPopulation()
    {
        return $this->_pool;
    }

    /**
     * Uses moment of inertia to calculate pair-wise Hamming distance as a measure of diversity.
     * See http://www.revolutionaryengineering.com/EA-01.pdf
     */
    public function getDiversity()
    {
        $summedCoords = array_fill(0, strlen($this->_genomeFactory->__invoke()->data), 0);
        foreach ($this->_pool as $x) {
            foreach (str_split($x->data) as $i=>$y) {
                $summedCoords[$i] = $summedCoords[$i] + $y;
            }
        }
        $popSize = $this->getPopulation()->count();
        $coords = (new ArrayCollection($summedCoords))->map(
            function ($i) use ($popSize) {
                return $i / $popSize;
            }
        );
        $diversities = $this->getPopulation()->map(
            function (Genome $x) use ($coords) {
                return array_sum(array_map(
                    function ($y, $c) {
                        return ($y - $c) * ($y - $c);
                    },
                    str_split($x->data),
                    $coords->toArray()
                ));
            }
        );
        $diversity = array_sum($diversities->toArray());

        return $popSize * $diversity;
    }
}
