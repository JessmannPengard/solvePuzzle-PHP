<?php

namespace Puzzle;

class Puzzle
{
    private int $cols;
    private int $rows;
    private array $pieces = [];


    public function getCols(): int
    {
        return $this->cols;
    }

    public function setCols(int $cols): void
    {
        $this->cols = $cols;
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function setRows(int $rows): void
    {
        $this->rows = $rows;
    }

    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function setPieces(array $pieces): void
    {
        $this->pieces = $pieces;
    }

    public function toString(): string
    {
        return "Columns: " . $this->cols . " - " . "Rows: " . $this->rows
            . "\nPieces:" . $this->showPieces($this->pieces);
    }

    private function showPieces(array $pieces): string
    {
        $strPieces = "";
        foreach ($pieces as $piece) {
            $strPieces .= "\n";
            foreach ($piece->getFaces() as $face) {
                $strPieces .= $face . " ";
            }
            $strPieces = rtrim($strPieces);
        }
        return $strPieces;
    }

    private static function handleError(string $message): void
    {
        echo "Error: " . $message . PHP_EOL;
    }

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
}
