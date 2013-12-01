<?php

namespace Contrebis\BlueJeans;


class Genome
{
    protected $_elite = false;

    /**
     * Royal road fitness function
     *
     * @return int
     */
    public function fitness()
    {
        return array_sum($this->data->getValues());
    }

    public function __construct(array $data = null, $elite = false)
    {
        if ($data === null) {
            $data = new ListCollection();
            for ($i = 0; $i < 16; $i++) {
                $data[] = mt_rand(0, 1);
            }
            $this->data = $data;
        } else {
            $this->data = new ListCollection($data);
        }
        $this->_elite = $elite;
    }
    
    public function isElite()
    {
        return $this->_elite;
    }

    /**
     * @param boolean $value
     */
    public function setElite($value)
    {
        $this->_elite = $value;
    }

    public function hash()
    {
        return md5($this->__toString());
    }

    public function eq(Genome $other)
    {
        return $this->data == $other->data;
    }
        
    public function cmp(Genome $other)
    {
        if ($this->fitness() == $other->fitness()) {
            return 0;
        }
        return $this->fitness() < $other->fitness() ? -1 : 1;
    }
        
    public function __toString()
    {
        return implode($this->data);
    }
        
    /**
     * Returns a mutated copy of this genome
     *
     * @param float $prob Probability that each bit will mutate
     * @return Genome
     */
    public function getMutated($prob)
    {
        if ($this->isElite()) {
            $prob = 0;
        }
        $newData = array();
        foreach ($this->data as $x) {
            if (lcg_value() < $prob) {
                $newData[] = $x ? 0 : 1;
            } else {
                $newData[] = $x;
            }
        }

        return new static($newData);
    }
                
    public function getCrossover(Genome $other, $position = null)
    {
        if ($this->isElite()) {
            return $this;
        }
        if ($position === null) {
            $position = mt_rand(1, $this->data->count() - 1);
        }
        $newData = array_merge(
            $this->data->slice(0, $position)->toArray(),
            $other->data->slice($position)->toArray()
        );

        return new static($newData);
    }

    public function isValid()
    {
        return true;
    }
}
