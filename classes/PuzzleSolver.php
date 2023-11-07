<?php

class PuzzleSolver
{
    private $puzzle;
    private $solutions;

    public function __construct($puzzle)
    {
        $this->puzzle = $puzzle;
        $this->solutions = [];
    }

    public function getSolutions()
    {
        return $this->solutions;
    }

    public function solve()
    {
        $this->solvePuzzle(null, 0, 0);
    }

    public function solvePuzzle($solution, $row, $col, &$usedPieces = [])
    {
        $width = $this->puzzle->getWidth();

        $nextRow = $row;
        $nextCol = $col + 1;
        if ($nextCol == $width) {
            $nextRow++;
            $nextCol = 0;
        }

        $pieces = $this->puzzle->getPieces();

        for ($i = 0; $i < count($pieces); $i++) {
            $piece = $pieces[$i];

            if (!in_array($piece, $usedPieces) && $this->isValidPlacement($solution, $piece, $row, $col)) {
                $solution[$row][$col] = $piece;
                $usedPieces[] = $piece;

                if (count($usedPieces) == count($pieces)) {
                    $this->solutions[] = $solution;
                }

                $this->solvePuzzle($solution, $nextRow, $nextCol, $usedPieces);

                // Undo
                $solution[$row][$col] = null;
                array_pop($usedPieces);
            }
        }
    }


    public function isValidPlacement($solution, $piece, $row, $col)
    {
        $width = $this->puzzle->getWidth();
        $height = $this->puzzle->getHeight();

        $topPiece = ($row > 0) ? $solution[$row - 1][$col] : null;
        $leftPiece = ($col > 0) ? $solution[$row][$col - 1] : null;

        if ($row == 0 && $col == 0) {
            //TOP LEFT CORNER, MUST BE CORNER
            //echo "<h6>Trying Top Left Corner " . $piece->getId() . "</h6>";
            if ($piece->isCorner()) {
                $piece->rotateToCorner("top-left");
                return true;
            }
        } elseif ($row == 0 && $col == $width - 1) {
            //TOP RIGHT CORNER, MUST BE CORNER AND FIT LEFT PIECE
            //echo "<h6>Trying Top Right Corner " . $piece->getId() . "</h6>";
            if ($piece->isCorner()) {
                $piece->rotateToCorner("top-right");
                if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                    return true;
                }
            }
        } elseif ($row == $height - 1 && $col == 0) {
            //BOTTOM LEFT CORNER, MUST BE CORNER AND FIT TOP PIECE
            //echo "<h6>Trying Bottom Left Corner " . $piece->getId() . "</h6>";
            if ($piece->isCorner()) {
                $piece->rotateToCorner("bottom-left");
                if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) {
                    return true;
                }
            }
        } elseif ($row == $height - 1 && $col == $width - 1) {
            //BOTTOM RIGHT CORNER, MUST BE CORNER AND FIT LEFT AND TOP PIECES
            //echo "<h6>Trying Top Right Corner " . $piece->getId() . "</h6>";
            if ($piece->isCorner()) {
                $piece->rotateToCorner("bottom-right");
                if (
                    $leftPiece->getFaces()[2] == $piece->getFaces()[0] &&
                    $topPiece->getFaces()[3] == $piece->getFaces()[1]
                ) {
                    return true;
                }
            }
        } elseif ($row == 0) {
            //TOP EDGE, MUST BE EDGE AND FIT LEFT PIECE
            //echo "<h6>Trying Top Edge " . $piece->getId() . "</h6>";
            if ($piece->isEdge()) {
                $piece->rotateToEdge("top");
                if ($leftPiece->getFaces()[2] == $piece->getFaces()[0]) {
                    return true;
                }
            }
        } elseif ($row == $height - 1) {
            //BOTTOM EDGE, MUST BE EDGE AND FIT LEFT AND TOP PIECES
            //echo "<h6>Trying Bottom Edge " . $piece->getId() . "</h6>";
            if ($piece->isEdge()) {
                $piece->rotateToEdge("bottom");
                if (
                    $leftPiece->getFaces()[2] == $piece->getFaces()[0] &&
                    $topPiece->getFaces()[3] == $piece->getFaces()[1]
                ) {
                    return true;
                }
            }
        } elseif ($col == 0) {
            //LEFT EDGE, MUST BE EDGE AND FIT TOP PIECE
            //echo "<h6>Trying Left Edge " . $piece->getId() . "</h6>";
            if ($piece->isEdge()) {
                $piece->rotateToEdge("left");
                if ($topPiece->getFaces()[3] == $piece->getFaces()[1]) return true;
            }
        } elseif ($col == $width - 1) {
            //RIGHT EDGE, MUST BE EDGE AND FIT LEFT AND TOP PIECE
            //echo "<h6>Trying Right Edge " . $piece->getId() . "</h6>";
            if ($piece->isEdge()) {
                $piece->rotateToEdge("right");
                if (
                    $leftPiece->getFaces()[2] == $piece->getFaces()[0] &&
                    $topPiece->getFaces()[3] == $piece->getFaces()[1]
                ) {
                    return true;
                }
            }
        } else {
            //INTERIOR, MUST BE INTERIOR AND FIT LEFT AND TOP
            //echo "<h6>Trying Interior " . $piece->getId() . "</h6>";
            if ($piece->isInterior()) {
                for ($rotation = 0; $rotation < 4; $rotation++) {
                    $piece->rotate($rotation);
                    if (
                        $leftPiece->getFaces()[2] == $piece->getFaces()[0] &&
                        $topPiece->getFaces()[3] == $piece->getFaces()[1]
                    ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
