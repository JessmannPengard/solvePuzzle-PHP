<?php

namespace Puzzle;

class PuzzlePiece
{
    private int $id;
    private array $faces = [];

    public function __construct(int $id, array $faces)
    {
        $this->id = $id;
        $this->faces = $faces;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFaces(): array
    {
        return $this->faces;
    }

    public function setFaces(array $faces): void
    {
        $this->faces = $faces;
    }

    public function rotate(): void
    {
        $currentFaces = $this->getFaces();
        $rotatedFaces = [];

        for ($i = 0; $i < 4; $i++) {
            $newIndex = ($i + 1) % 4;
            $rotatedFaces[$newIndex] = $currentFaces[$i];
        }

        $this->setFaces($rotatedFaces);
    }

    public function rotateToCorner(string $position): void
    {
        $targetPattern = [];

        switch ($position) {
            case "top-left":
                $targetPattern = [0, 0, -1, -1];
                break;
            case "top-right":
                $targetPattern = [-1, 0, 0, -1];
                break;
            case "bottom-right":
                $targetPattern = [-1, -1, 0, 0];
                break;
            case "bottom-left":
                $targetPattern = [0, -1, -1, 0];
                break;
            default:
                throw new \Exception("Invalid position");
        }

        while (!$this->matchesPattern($this->getFaces(), $targetPattern)) {
            $this->rotate();
        }
    }

    public function rotateToEdge(string $position): void
    {
        $targetPattern = [];

        switch ($position) {
            case "left":
                $targetPattern = [0, -1, -1, -1];
                break;
            case "top":
                $targetPattern = [-1, 0, -1, -1];
                break;
            case "right":
                $targetPattern = [-1, -1, 0, -1];
                break;
            case "bottom":
                $targetPattern = [-1, -1, -1, 0];
                break;
            default:
                throw new \Exception("Invalid position");
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

    public function isCorner(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 2;
    }

    public function isEdge(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 1;
    }

    public function isInterior(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 0;
    }

    private function countBorders(): int
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
