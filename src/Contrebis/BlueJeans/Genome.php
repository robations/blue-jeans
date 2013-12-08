<?php

namespace Contrebis\BlueJeans;


class Genome
{
    protected $elite = false;

    protected $genomeFactory;

    /**
     * Royal road fitness function
     *
     * @return int
     */
    public function fitness()
    {
        return strpos($this->data, '1') !== false ? count_chars($this->data)[49] : 0;
    }

    public function __construct(GenomeFactory $factory, $data = null, $elite = false)
    {
        if ($data === null) {
            $data = '';
            for ($i = 0; $i < 16; $i++) {
                $data .= mt_rand(0, 1);
            }
            $this->data = $data;
        } else {
            $this->data = $data;
        }
        $this->elite = $elite;
        $this->genomeFactory = $factory;
    }
    
    public function isElite()
    {
        return $this->elite;
    }

    /**
     * @param boolean $value
     */
    public function setElite($value)
    {
        $this->elite = $value;
    }

    public function hash()
    {
        return md5($this->__toString());
    }

    public function eq(Genome $other)
    {
        return $this->data === $other->data;
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
        return $this->data;
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
        $newData = '';
        for ($i = 0; $i < strlen($this->data); $i++) {
            $x = (int) $this->data[$i];
            if (lcg_value() < $prob) {
                $newData .= $x ? 0 : 1;
            } else {
                $newData .= $x;
            }
        }

        return $this->genomeFactory->__invoke($newData);
    }

    public function getCrossover(Genome $other, $position = null)
    {
        if ($this->isElite()) {
            return $this;
        }
        if ($position === null) {
            $position = mt_rand(1, strlen($this->data) - 1);
        }
        $newData = substr($this->data, 0, $position) . substr($other, $position);

        return $this->genomeFactory->__invoke($newData);
    }

    /**
     * @return bool Returns true if genome represents a valid solution to the problem
     */
    public function isValid()
    {
        return true;
    }
}
