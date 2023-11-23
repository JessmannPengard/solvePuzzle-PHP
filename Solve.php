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

    echo "Solving..." . PHP_EOL;
    $solver = new PuzzleSolver($puzzle);

    $startTime = microtime(true);
    $solver->solve();
    $endTime = microtime(true);

    $executionTime = $endTime - $startTime;

    $solutions = $solver->getSolutionsAsString();
    echo $solutions;

    echo "Solved in " . number_format($executionTime, 4) . " secs.";
}


// Example usage:
// php solve.php puzzles/2x10.txt
