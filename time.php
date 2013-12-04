<?php

use Contrebis\BlueJeans\Genome;
use Contrebis\BlueJeans\StringGenome;

require_once 'vendor/autoload.php';

class Test
{
    use \Contrebis\BlueJeans\Test\GenomeFixture;
}

$time = 5;
$fixtures = new Test();
$factory = new \Contrebis\BlueJeans\StringGenomeFactory();
$genome1 = $fixtures->getAllZerosGenome();
$genome2 = $fixtures->getAllOnesGenome();

$start = microtime(true);
$i = 0;
while (microtime(true) - $start < $time) {
    $genome1 = $genome2->getCrossover($genome2);
    $genome2 = $genome1->getMutated(0.5);
    $i++;
}

printf("Iterations: %d\nTime: %ss\n", $i, $time);
