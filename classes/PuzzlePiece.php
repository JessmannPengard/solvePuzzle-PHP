<?php

class PuzzlePiece
{
    private $id;
    private $faces = [];

    public function __construct($id, $faces)
    {
        $this->id = $id;
        $this->faces = $faces;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFaces()
    {
        return $this->faces;
    }

    public function setFaces($faces)
    {
        $this->faces = $faces;
    }

    public function isCorner()
    {
        $faces = $this->getFaces();
        return count(array_filter($faces, function ($face) {
            return $face === "0";
        })) === 2;
    }

    public function isEdge()
    {
        $faces = $this->getFaces();
        return count(array_filter($faces, function ($face) {
            return $face === "0";
        })) === 1;
    }

    public function isInterior()
    {
        return !$this->isCorner() && !$this->isEdge();
    }

    public function rotateToCorner($targetOrientation)
    {
        $faces = $this->getFaces();


        while (!($faces[0] == "0" && $faces[1] == 0)) {
            $firstElement = array_shift($faces);
            $faces[] = $firstElement;
        }

        switch ($targetOrientation) {
            case 'top-left':
                break;
            case 'top-right':
                $faces = [$faces[3], "0", "0", $faces[2]];
                break;
            case 'bottom-right':
                $faces = [$faces[2], $faces[3], "0", "0"];
                break;
            case 'bottom-left':
                $faces = ["0", $faces[2], $faces[3], "0"];
                break;
            default:
                break;
        }

        $this->setFaces($faces);
    }

    public function rotateToEdge($targetOrientation)
    {
        $faces = $this->getFaces();

        while (!($faces[0] == "0")) {
            $firstElement = array_shift($faces);
            $faces[] = $firstElement;
        }

        switch ($targetOrientation) {
            case 'left':
                // [0, a, b, c]
                break;
            case 'top':
                // [a, 0, b, c]
                $faces = [$faces[3], "0", $faces[1], $faces[2]];
                break;
            case 'right':
                // [a, b, 0, c]
                $faces = [$faces[2], $faces[3], "0",  $faces[1]];
                break;
            case 'bottom':
                // [a, b, c, 0]
                $faces = [$faces[1], $faces[2], $faces[3], "0"];
                break;
            default:
                break;
        }

        $this->setFaces($faces);
    }

    public function rotate($amount)
    {
        $faces = $this->getFaces();

        switch ($amount) {
            case 1:
                $rotatedFaces = [
                    $faces[3],
                    $faces[0],
                    $faces[1],
                    $faces[2]
                ];
                break;
            case 2:
                $rotatedFaces = [
                    $faces[2],
                    $faces[3],
                    $faces[0],
                    $faces[1]
                ];
                break;
            case 3:
                $rotatedFaces = [
                    $faces[1],
                    $faces[2],
                    $faces[3],
                    $faces[0]
                ];
                break;
            default:
                return;
        }

        $this->setFaces($rotatedFaces);
    }
}
