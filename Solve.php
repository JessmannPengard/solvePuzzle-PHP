<?php

require_once __DIR__ . '/autoload.php';

use Puzzle\Puzzle;
use Puzzle\PuzzleSolver;


if ($_SERVER["argc"] < 2) {
    echo "Usage: solve [filename]\n";
    return;
}

$fileName = $_SERVER["argv"][1];
$puzzle = Puzzle::loadPuzzle($fileName);

if ($puzzle !== null) {
    echo $puzzle->toString() . PHP_EOL;

    $solver = new PuzzleSolver($puzzle);
    $solver->solve();

    $solutions = $solver->getSolutionsAsString();
    echo $solutions . PHP_EOL;
}


// Example usage:
// php solve.php puzzles/2x10.txt
