<?php

// autoload.php includes the necessary class autoloader
require_once __DIR__ . '/autoload.php';

use Puzzle\Puzzle;
use Puzzle\PuzzleSolver;


// Check if the correct number of command line arguments is provided
if ($_SERVER["argc"] < 2) {
    echo "Usage: solve [filename]\n";
    return;
}

// Retrieve the filename from command line arguments
$fileName = $_SERVER["argv"][1];

// Load the puzzle from the specified file
$puzzle = Puzzle::loadPuzzle($fileName);

// Check if the puzzle was successfully loaded
if ($puzzle !== null) {
    // Display the puzzle information
    echo $puzzle->toString() . PHP_EOL;

    echo "Solving..." . PHP_EOL;

    // Create a puzzle solver instance
    $solver = new PuzzleSolver($puzzle);

    // Record the start time for measuring execution time
    $startTime = microtime(true);

    // Solve the puzzle
    $solver->solve();

    // Record the end time
    $endTime = microtime(true);

    // Calculate the execution time
    $executionTime = $endTime - $startTime;

    // Get the solutions as a formatted string
    $solutions = $solver->getSolutionsAsString();

    // Display the solutions
    echo $solutions;

    // Display the total execution time
    echo "Solved in " . number_format($executionTime, 4) . " secs.";
}


// Example usage:
// php solve.php puzzles/2x10.txt
