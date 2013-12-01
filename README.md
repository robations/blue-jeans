# Genetic algorithm framework

## Introduction

This component is/will be a framework for solving optimisation problems using genetic algorithms.

The idea is you create a random pool of 'genomes'. Your genome class extends `Genome` and must interpret your bit string into a solution to your problem and calculate a fitness value for the solution. Good solutions are more likely to survive into the next generation.

Configurable options are:

- Elitism: how many of the fittest genomes survive to the next generation intact.
- Mutation rate
- Crossover rate
- Population size

An indicator of diversity can be calculated to show how much entropy remains in the system. If wanted, the mutation rate could vary to keep the diversity above a certain threshold.

## TODO

- Better test coverage (and remove whatever bugs are still lurking in there)
- Some documentation
- Optimise list collection class
- Other performance optimisations
- Add a license

