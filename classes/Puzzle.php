<?php

class Puzzle
{
    private $width;
    private $height;
    private $pieces = [];

    public function __construct($fileContent)
    {
        $this->loadPuzzle($fileContent);
    }

    public function loadPuzzle($fileContent)
    {
        $lines = explode("\n", $fileContent);

        list($this->width, $this->height) = explode(" ", trim(array_shift($lines)));

        $pieceId = 1;
        foreach ($lines as $line) {
            $pieceData = explode(" ", $line);
            $pieceData = array_map('trim', $pieceData);

            $piece = new PuzzlePiece($pieceId, $pieceData);
            $this->addPiece($piece);

            $pieceId++;
        }
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function addPiece(PuzzlePiece $piece)
    {
        $this->pieces[] = $piece;
    }

    public function getPieces()
    {
        return $this->pieces;
    }

    public function getCornerPieces()
    {
        $cornerPieces = array_filter($this->pieces, function ($piece) {
            return $piece->isCorner();
        });
        return array_values($cornerPieces);
    }

    public function getEdgePieces()
    {
        $edgePieces = array_filter($this->pieces, function ($piece) {
            return $piece->isEdge();
        });

        return array_values($edgePieces);
    }

    public function getInteriorPieces()
    {
        $interiorPieces = array_filter($this->pieces, function ($piece) {
            return !$piece->isCorner() && !$piece->isEdge();
        });

        return array_values($interiorPieces);
    }
}
