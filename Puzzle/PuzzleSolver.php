<?php

namespace Puzzle;

/**
 * Class PuzzleSolver
 *
 * This class is responsible for solving a given puzzle and storing the solutions.
 */
class PuzzleSolver
{

    /**
     * @var Puzzle The puzzle to be solved.
     */
    private Puzzle $puzzle;

    /**
     * @var array An array to store the solutions found during the solving process.
     */
    private array $solutions = [];

    /**
     * PuzzleSolver constructor.
     *
     * @param Puzzle $puzzle The puzzle to be solved.
     */
    public function __construct(Puzzle $puzzle)
    {
        $this->puzzle = $puzzle;
    }

    /**
     * Get the solutions as a formatted string.
     *
     * @param bool $forWeb If true, use HTML line breaks; otherwise, use plain text line breaks.
     * @return string A string representation of the solutions.
     */
    public function getSolutionsAsString(bool $forWeb = false): string
    {
        $separator = $forWeb ? "<br>" : "\n";
        $result = $separator . "Solution(s)" . $separator;
        foreach ($this->solutions as $solution) {
            foreach ($solution as $row) {
                foreach ($row as $piece) {
                    $result .= ($piece !== null) ? $piece->getId() . " " : "null ";
                }
                $result .= $separator;
            }
            $result .= $separator;
        }
        return $result;
    }

    /**
     * Solve the puzzle.
     */
    public function solve(): void
    {
        $currentSolution = array_fill(0, $this->puzzle->getRows(), array_fill(0, $this->puzzle->getCols(), null));

        $this->solvePuzzle(0, 0, $currentSolution, array());
    }

    /**
     * Recursively solve the puzzle using backtracking.
     *
     * @param int $row The current row.
     * @param int $col The current column.
     * @param array $currentSolution The current state of the puzzle solution.
     * @param array $usedPieces An array of used puzzle pieces.
     */
    private function solvePuzzle(int $row, int $col, array $currentSolution, array $usedPieces): void
    {
        $numPieces = count($this->puzzle->getPieces());

        // Calculate next row and column
        $nextRow = $row;
        $nextCol = $col + 1;
        if ($nextCol == $this->puzzle->getCols()) {
            $nextRow++;
            $nextCol = 0;
        }

        // Base case: If we've placed all the pieces, we found a solution
        if (count($usedPieces) == $numPieces) {
            // Save the current solution to the solutions array
            $this->solutions[] = $currentSolution;
            return;
        }

        $pieces = $this->puzzle->getPieces();

        if ($row == 0 && $col == 0) {
            // Find fixed top left corner to avoid rotated solutions
            $fixedCornerPiece = $this->findFixedCornerPiece($pieces);

            // Add to used pieces
            $usedPieces[] = $fixedCornerPiece;

            // Place the piece in the current solution
            $currentSolution[$row][$col] = $fixedCornerPiece;

            // Recursively try to solve the puzzle with the updated solution
            $this->solvePuzzle($nextRow, $nextCol, $currentSolution, $usedPieces);
        } else {
            // Iterate through all pieces and try placing them
            for ($i = 0; $i < $numPieces; $i++) {
                $currentPiece = $pieces[$i];

                if (!in_array($currentPiece, $usedPieces) && $this->tryPiece($row, $col, $currentPiece, $currentSolution)) {
                    // Add to used pieces
                    $usedPieces[] = $currentPiece;

                    // Place the piece in the current solution
                    $currentSolution[$row][$col] = $currentPiece;

                    // Recursively try to solve the puzzle with the updated solution
                    $this->solvePuzzle($nextRow, $nextCol, $currentSolution, $usedPieces);

                    // Backtrack: Undo the changes made for backtracking
                    $currentSolution[$row][$col] = null;
                    array_pop($usedPieces);
                }
            }
        }
    }

    /**
     * Find a fixed corner piece to avoid rotated solutions.
     *
     * @param array $pieces The array of puzzle pieces.
     * @return PuzzlePiece|null The fixed corner piece.
     */
    private function findFixedCornerPiece(array $pieces): PuzzlePiece
    {
        foreach ($pieces as $piece) {
            if ($this->puzzle->isOneDimensional()) {
                if ($piece->isLinearCorner()) {
                    if ($this->puzzle->getRows() == 1) {
                        $piece->rotateToCorner("left");
                    } else {
                        $piece->rotateToCorner("top");
                    }
                    return $piece;
                }
            } else {
                if ($piece->isCorner()) {
                    $piece->rotateToCorner("top-left");
                    return $piece;
                }
            }
        }
        return null;
    }

    /**
     * Try placing a puzzle piece in the solution at the given position.
     *
     * @param int $row The current row.
     * @param int $col The current column.
     * @param PuzzlePiece $piece The puzzle piece to be placed.
     * @param array $solution The current state of the puzzle solution.
     * @return bool True if the piece can be placed; otherwise, false.
     */
    private function tryPiece(int $row, int $col, PuzzlePiece $piece, array &$solution): bool
    {
        $width = $this->puzzle->getCols();
        $height = $this->puzzle->getRows();

        $topPiece = ($row > 0) ? $solution[$row - 1][$col] : null;
        $leftPiece = ($col > 0) ? $solution[$row][$col - 1] : null;

        if ($this->puzzle->isOneDimensional()) {
            // One-dimensional puzzle
            if ($height == 1) {
                // X linear
                if ($col == 0) {
                    // Left Corner
                    if ($piece->isLinearCorner()) {
                        $piece->rotateToCorner("left");
                        return true;
                    }
                } else if ($col < $width - 1) {
                    // Interior X linear
                    if ($piece->isDoubleEdge()) {
                        if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                            return true;
                        }
                        for ($i = 0; $i < 3; $i++) {
                            $piece->rotate();
                            if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                                return true;
                            }
                        }
                    }
                } else {
                    // Right Corner
                    if ($piece->isLinearCorner()) {
                        $piece->rotateToCorner("right");
                        return true;
                    }
                }
            } else {
                // Y linear
                if ($row == 0) {
                    // Top corner
                    if ($piece->isLinearCorner()) {
                        $piece->rotateToCorner("top");
                        return true;
                    }
                } else if ($row < $height - 1) {
                    // Interior Y linear
                    if ($piece->isDoubleEdge()) {
                        if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) {
                            return true;
                        }
                        for ($i = 0; $i < 3; $i++) {
                            $piece->rotate();
                            if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) {
                                return true;
                            }
                        }
                    }
                } else {
                    // Bottom corner
                    if ($piece->isLinearCorner()) {
                        $piece->rotateToCorner("bottom");
                        return true;
                    }
                }
            }
        } else {
            // Square or rectangular puzzle
            if ($row == 0) {
                // First row
                if ($col == 0) {
                    // Top Left Corner
                    if ($piece->isCorner()) {
                        $piece->rotateToCorner("top-left");
                        return true;
                    }
                } else if ($col == $width - 1) {
                    // Top Right Corner
                    if ($piece->isCorner()) {
                        $piece->rotateToCorner("top-right");
                        if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                            return true;
                        }
                    }
                } else {
                    // Top Edge
                    if ($piece->isEdge()) {
                        $piece->rotateToEdge("top");
                        if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                            return true;
                        }
                    }
                }
            } else if ($row == $height - 1) {
                // Last row
                if ($col == 0) {
                    // Bottom Left Corner
                    if ($piece->isCorner()) {
                        $piece->rotateToCorner("bottom-left");
                        if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) {
                            return true;
                        }
                    }
                } else if ($col == $width - 1) {
                    // Bottom Right Corner
                    if ($piece->isCorner()) {
                        $piece->rotateToCorner("bottom-right");
                        if (
                            $topPiece->getFaces()[3] == $piece->getFaces()[1]
                            && $leftPiece->getFaces()[2] == $piece->getFaces()[0]
                        ) {
                            return true;
                        }
                    }
                } else {
                    // Bottom Edge
                    if ($piece->isEdge()) {
                        $piece->rotateToEdge("bottom");
                        if (
                            $topPiece->getFaces()[3] == $piece->getFaces()[1]
                            && $leftPiece->getFaces()[2] == $piece->getFaces()[0]
                        ) {
                            return true;
                        }
                    }
                }
            } else {
                // Intermediate rows
                if ($col == 0) {
                    // Left Edge
                    if ($piece->isEdge()) {
                        $piece->rotateToEdge("left");
                        if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) {
                            return true;
                        }
                    }
                } else if ($col == $width - 1) {
                    // Right Edge
                    if ($piece->isEdge()) {
                        $piece->rotateToEdge("right");
                        if (
                            $topPiece->getFaces()[3] == $piece->getFaces()[1]
                            && $leftPiece->getFaces()[2] == $piece->getFaces()[0]
                        ) {
                            return true;
                        }
                    }
                } else {
                    // Interior
                    if ($piece->isInterior()) {
                        if (
                            $topPiece->getFaces()[3] == $piece->getFaces()[1]
                            && $leftPiece->getFaces()[2] == $piece->getFaces()[0]
                        ) {
                            return true;
                        }
                        for ($i = 0; $i < 3; $i++) {
                            $piece->rotate();
                            if (
                                $topPiece->getFaces()[3] == $piece->getFaces()[1]
                                && $leftPiece->getFaces()[2] == $piece->getFaces()[0]
                            ) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
}
