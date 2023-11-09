<?php

class PuzzlePiece
{
    private $id;
    private $faces;

    public function __construct($id, $faces)
    {
        $this->id = $id;
        $this->faces = $faces;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFaces()
    {
        return $this->faces;
    }

    public function setFaces($faces)
    {
        $this->faces = $faces;
    }

    public function rotate()
    {
        $currentFaces = $this->getFaces();
        $rotatedFaces = array();

        for ($i = 0; $i < 4; $i++) {
            $newIndex = ($i + 1) % 4;
            $rotatedFaces[$newIndex] = $currentFaces[$i];
        }

        $this->setFaces($rotatedFaces);
    }

    public function rotateToCorner($position)
    {
        $targetPattern = array();

        switch ($position) {
            case "top-left":
                $targetPattern = array(0, 0, -1, -1);
                break;
            case "top-right":
                $targetPattern = array(-1, 0, 0, -1);
                break;
            case "bottom-right":
                $targetPattern = array(-1, -1, 0, 0);
                break;
            case "bottom-left":
                $targetPattern = array(0, -1, -1, 0);
                break;
            default:
                throw new Exception("Invalid position");
        }

        while (!$this->matchesPattern($this->getFaces(), $targetPattern)) {
            $this->rotate();
        }
    }

    public function rotateToEdge($position)
    {
        $targetPattern = array();

        switch ($position) {
            case "left":
                $targetPattern = array(0, -1, -1, -1);
                break;
            case "top":
                $targetPattern = array(-1, 0, -1, -1);
                break;
            case "right":
                $targetPattern = array(-1, -1, 0, -1);
                break;
            case "bottom":
                $targetPattern = array(-1, -1, -1, 0);
                break;
            default:
                throw new Exception("Invalid position");
        }

        while (!$this->matchesPattern($this->getFaces(), $targetPattern)) {
            $this->rotate();
        }
    }

    private function matchesPattern($faces, $pattern)
    {
        for ($i = 0; $i < 4; $i++) {
            if ($pattern[$i] !== -1 && $faces[$i] !== $pattern[$i]) {
                return false;
            }
        }
        return true;
    }

    public function isCorner()
    {
        $numBorders = $this->countBorders();
        return $numBorders == 2;
    }

    public function isEdge()
    {
        $numBorders = $this->countBorders();
        return $numBorders == 1;
    }

    public function isInterior()
    {
        $numBorders = $this->countBorders();
        return $numBorders == 0;
    }

    private function countBorders()
    {
        $numBorders = 0;
        foreach ($this->faces as $face) {
            if ($face == 0) {
                $numBorders++;
            }
        }
        return $numBorders;
    }
}
