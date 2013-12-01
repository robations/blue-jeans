<?php

use Contrebis\BlueJeans\Pool;
use Contrebis\BlueJeans\Tsp\TspFactory2;

require_once 'vendor/autoload.php';


$population = array();
$mutationProb = 0.05;
$crossoverProb = 0.62;
$elitism = 5;
$freshBlood = 0;

$x = new Pool(24, new TspFactory2());

printf("Initial population: mean fitness=%s\n", $x->getMeanFitness(true));
echo $x . "\n";

$highestMax = $x->getMaxFitness(true);
$highestMean = $x->getMeanFitness(true);
$previousBest = null;
$best = null;
$i = 0;
while (true) {
    $newFitnessMax = false;
    $newFitnessMeanMax = false;

    $x = $x->getSelectionPool($elitism, $freshBlood);
    $x->crossover($crossoverProb);
    $x->mutate($mutationProb);

    if ($x->getMaxFitness(true) > $highestMax) {
        $highestMax = $x->getMaxFitness(true);
        $previousBest = $best;
        $best = $x->getFittestGenomes(1, true)[0];
        $newFitnessMax = true;
    }

    if ($x->getMeanFitness(true) > $highestMean) {
        $highestMean = $x->getMeanFitness(true);
        $newFitnessMeanMax = true;
    }

    if ($i % 50 === 0 or $newFitnessMax or $newFitnessMeanMax) {
        printf("Generation %d:%1smean valid fitness=%.1f,%1smax valid fitness=%.1f, diversity=%.1f\n",
            $i,
            $newFitnessMeanMax ? '*' : '',
            $x->getMeanFitness(true),
            $newFitnessMax ? '*' : '',
            $x->getMaxFitness(true),
            $x->getDiversity()
        );

        if ($best !== null and $newFitnessMax) {
            if ($previousBest !== null) {
                printf("Previous best was:\n%s\n", $previousBest);
            }
            printf("Best solution so far:\n%s\n", $best);
        }
    }

    if ($i % 500 == 499) {
        echo "Carry on? (y|n)\n";
        $line = fgets(STDIN);
        if (strtolower($line) === "n") {
            break;
        }
    }
    $i += 1;
}

printf("Generation %d: mean fitness=%.1f, max fitness=%.1f\n", $i, $x->getMeanFitness(true), $x->getMaxFitness(true));
printf("Best solution was:\n%s\n", $best);
printf("Genome:\n");
printf(implode("", $best->data));
echo "\n";
