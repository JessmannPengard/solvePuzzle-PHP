<?php

class PuzzleSolver
{

    private $puzzle;
    private $solutions = array();

    public function __construct($puzzle)
    {
        $this->puzzle = $puzzle;
    }

    public function getSolutionsAsString()
    {
        $result = "";
        foreach ($this->solutions as $solution) {
            foreach ($solution as $row) {
                foreach ($row as $piece) {
                    $result .= ($piece !== null) ? $piece->getId() . " " : "null ";
                }
                $result .= "<br>";
            }
            $result .= "<br>";
        }
        return $result;
    }

    public function solve()
    {
        $currentSolution = array_fill(0, $this->puzzle->getRows(), array_fill(0, $this->puzzle->getCols(), null));
        
        $this->solvePuzzle(0, 0, $currentSolution, array());
    }

    private function solvePuzzle($row, $col, $currentSolution, $usedPieces)
    {
        $numPieces = count($this->puzzle->getPieces());

        // Calculate next row and column
        $nextRow = $row;
        $nextCol = $col + 1;
        if ($nextCol == $this->puzzle->getRows()) {
            $nextRow++;
            $nextCol = 0;
        }

        // Base case: If we've placed all the pieces, we found a solution
        if (count($usedPieces) == count($this->puzzle->getPieces())) {
            // Save the current solution to the solutions array
            $this->solutions[] = $currentSolution;
            return;
        }

        $pieces = $this->puzzle->getPieces();

        if ($row == 0 && $col == 0) {
            // Find fixed top left corner to avoid rotated solutions
            $fixedCornerPiece = $this->findFixedCornerPiece($pieces);

            if ($fixedCornerPiece !== null && !in_array($fixedCornerPiece, $usedPieces) && $this->tryPiece($row, $col, $fixedCornerPiece, $currentSolution)) {
                $usedPieces[] = $fixedCornerPiece;
                $currentSolution[$row][$col] = $fixedCornerPiece;

                // Recursively try to solve the puzzle with the updated solution
                $this->solvePuzzle($nextRow, $nextCol, $currentSolution, $usedPieces);

                // Backtrack: Undo the changes made for backtracking
                $currentSolution[$row][$col] = null;
                array_pop($usedPieces);
            }
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

    private function findFixedCornerPiece($pieces)
    {
        foreach ($pieces as $piece) {
            if ($piece->isCorner()) {
                return $piece;
            }
        }
        return null;
    }

    private function tryPiece($row, $col, $piece, &$solution)
    {
        $width = $this->puzzle->getCols();
        $height = $this->puzzle->getRows();

        $topPiece = ($row > 0) ? $solution[$row - 1][$col] : null;
        $leftPiece = ($col > 0) ? $solution[$row][$col - 1] : null;

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
        return false;
    }
}
