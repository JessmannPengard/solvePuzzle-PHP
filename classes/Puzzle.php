<?php

class Puzzle
{
    private $cols;
    private $rows;
    private $pieces = [];

    public function __construct($fileContent)
    {
        try {
            $lines = explode("\n", $fileContent);
            $firstRow = array_shift($lines);
            $dimensions = explode(" ", $firstRow);
            $cols = (int)$dimensions[0];
            $rows = (int)$dimensions[1];

            $pieces = array();

            foreach ($lines as $line) {
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

            $expectedNumPieces = $cols * $rows;
            if ($expectedNumPieces !== count($pieces)) {
                self::handleError("The number of pieces does not fit puzzle dimensions.");
                return null;
            }

            $this->cols = $cols;
            $this->rows = $rows;
            $this->pieces = $pieces;
        } catch (Exception $e) {
            self::handleError("Error processing content: " . $e->getMessage());
            return null;
        }
    }

    public function getCols()
    {
        return $this->cols;
    }

    public function setCols($cols)
    {
        $this->cols = $cols;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    public function getPieces()
    {
        return $this->pieces;
    }

    public function setPieces($pieces)
    {
        $this->pieces = $pieces;
    }

    public function toString()
    {
        return "Columns: " . $this->cols . " - " . "Rows: " . $this->rows
            . "<br>Pieces:" . $this->showPieces($this->pieces);
    }

    private function showPieces($pieces)
    {
        $strPieces = "";
        foreach ($pieces as $piece) {
            $strPieces .= "<br>";
            foreach ($piece->getFaces() as $face) {
                $strPieces .= $face . " ";
            }
            $strPieces = rtrim($strPieces);
        }
        return $strPieces;
    }

    private static function handleError($message)
    {
        echo "Error: " . $message . PHP_EOL;
    }
}
