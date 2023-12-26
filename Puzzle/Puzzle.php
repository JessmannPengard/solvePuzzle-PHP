<?php

namespace Puzzle;

/**
 * Class Puzzle
 * @package Puzzle
 */
class Puzzle
{
    /**
     * @var int The number of columns in the puzzle
     */
    private int $cols;

    /**
     * @var int The number of rows in the puzzle
     */
    private int $rows;

    /**
     * @var array The pieces in the puzzle
     */
    private array $pieces = [];

    /**
     * Get the number of columns in the puzzle
     * @return int The number of columns
     */
    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * Set the number of columns in the puzzle
     * @param int $cols The number of columns
     */
    public function setCols(int $cols): void
    {
        $this->cols = $cols;
    }

    /**
     * Get the number of rows in the puzzle
     * @return int The number of rows
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * Set the number of rows in the puzzle
     * @param int $rows The number of rows
     */
    public function setRows(int $rows): void
    {
        $this->rows = $rows;
    }

    /**
     * Get the pieces in the puzzle
     * @return array The puzzle pieces
     */
    public function getPieces(): array
    {
        return $this->pieces;
    }

    /**
     * Set the pieces in the puzzle
     * @param array $pieces The puzzle pieces
     */
    public function setPieces(array $pieces): void
    {
        $this->pieces = $pieces;
    }

    /**
     * Get a string representation of the puzzle
     * @param bool $forWeb Whether the output is for web (HTML)
     * @return string The string representation of the puzzle
     */
    public function toString(bool $forWeb = false): string
    {
        $separator = $forWeb ? "<br>" : "\n";
        return "Columns: " . $this->cols . " - " . "Rows: " . $this->rows
            . $separator . "Pieces:" . $this->showPieces($this->pieces, $forWeb);
    }

    /**
     * Get a string representation of the puzzle pieces
     * @param array $pieces The puzzle pieces
     * @param bool $forWeb Whether the output is for web (HTML)
     * @return string The string representation of the puzzle pieces
     */
    private function showPieces(array $pieces, bool $forWeb = false): string
    {
        $strPieces = "";
        $separator = $forWeb ? "<br>" : "\n";
        foreach ($pieces as $piece) {
            $strPieces .= $separator;
            foreach ($piece->getFaces() as $face) {
                $strPieces .= $face . " ";
            }
            $strPieces = rtrim($strPieces);
        }
        return $strPieces;
    }

    /**
     * Handle errors and display an error message
     * @param string $message The error message
     */
    private static function handleError(string $message): void
    {
        echo "Error: " . $message . PHP_EOL;
    }

    /**
     * Load a puzzle from a file
     * @param string $fileName The name of the file containing the puzzle
     * @return Puzzle|null The loaded puzzle, or null if an error occurs
     */
    public static function loadPuzzle(string $fileName): ?self
    {
        try {
            $fileContents = file_get_contents($fileName);

            $lines = explode("\n", $fileContents);
            $firstRow = array_shift($lines);
            $dimensions = explode(" ", $firstRow);
            $cols = (int)$dimensions[0];
            $rows = (int)$dimensions[1];

            $pieces = [];

            foreach ($lines as $line) {
                if ($line !== "") {
                    $facesValues = explode(" ", $line);

                    if (count($facesValues) !== 4) {
                        self::handleError("Invalid piece format.");
                        return null;
                    }

                    $id = count($pieces) + 1;
                    $faces = array_map('intval', $facesValues);

                    $piece = new PuzzlePiece($id, $faces);
                    $pieces[] = $piece;
                }
            }

            $expectedNumPieces = $cols * $rows;
            if ($expectedNumPieces !== count($pieces)) {
                self::handleError("The number of pieces does not fit puzzle dimensions.");
                return null;
            }

            $puzzle = new self();
            $puzzle->cols = $cols;
            $puzzle->rows = $rows;
            $puzzle->pieces = $pieces;

            return $puzzle;
        } catch (\Exception $e) {
            self::handleError("Error processing content: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the puzzle is one-dimensional
     * @return bool Whether the puzzle is one-dimensional
     */
    public function isOneDimensional(): bool
    {
        return $this->getRows() == 1 || $this->getCols() == 1;
    }
}
