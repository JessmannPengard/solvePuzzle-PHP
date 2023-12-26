<?php

namespace Puzzle;

/**
 * Class PuzzlePiece
 * @package Puzzle
 */
class PuzzlePiece
{
    /**
     * @var int The ID of the puzzle piece
     */
    private int $id;

    /**
     * @var array The faces of the puzzle piece
     */
    private array $faces = [];

    /**
     * PuzzlePiece constructor.
     * @param int $id The ID of the puzzle piece
     * @param array $faces The faces of the puzzle piece
     */
    public function __construct(int $id, array $faces)
    {
        $this->id = $id;
        $this->faces = $faces;
    }

    /**
     * Get the ID of the puzzle piece
     * @return int The ID of the puzzle piece
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the ID of the puzzle piece
     * @param int $id The ID of the puzzle piece
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the faces of the puzzle piece
     * @return array The faces of the puzzle piece
     */
    public function getFaces(): array
    {
        return $this->faces;
    }

    /**
     * Set the faces of the puzzle piece
     * @param array $faces The faces of the puzzle piece
     */
    public function setFaces(array $faces): void
    {
        $this->faces = $faces;
    }

    /**
     * Rotate the puzzle piece
     */
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

    /**
     * Rotate the puzzle piece to a specific corner position
     * @param string $position The target corner position
     */
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
            case "left":
                $targetPattern = [0, 0, -1, 0];
                break;
            case "right":
                $targetPattern = [-1, 0, 0, 0];
                break;
            case "top":
                $targetPattern = [0, 0, 0, -1];
                break;
            case "bottom":
                $targetPattern = [0, -1, 0, 0];
                break;
            default:
                throw new \Exception("Invalid position");
        }

        while (!$this->matchesPattern($this->getFaces(), $targetPattern)) {
            $this->rotate();
        }
    }

    /**
     * Rotate the puzzle piece to a specific edge position
     * @param string $position The target edge position
     */
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

    /**
     * Check if the puzzle piece matches a specific pattern
     * @param array $faces The faces of the puzzle piece
     * @param array $pattern The target pattern to match
     * @return bool Whether the puzzle piece matches the pattern
     */
    private function matchesPattern($faces, $pattern)
    {
        for ($i = 0; $i < 4; $i++) {
            if ($pattern[$i] !== -1 && $faces[$i] !== $pattern[$i]) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the puzzle piece represents a corner
     * @return bool Whether the puzzle piece is a corner piece
     */
    public function isCorner(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 2;
    }

    /**
     * Check if the puzzle piece represents a linear corner
     * @return bool Whether the puzzle piece is a linear corner piece
     */
    public function isLinearCorner(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 3;
    }

    /**
     * Check if the puzzle piece represents an edge
     * @return bool Whether the puzzle piece is an edge piece
     */
    public function isEdge(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 1;
    }

    /**
     * Check if the puzzle piece represents a double edge
     * @return bool Whether the puzzle piece is a double edge piece
     */
    public function isDoubleEdge(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 2;
    }

    /**
     * Check if the puzzle piece represents an interior piece
     * @return bool Whether the puzzle piece is an interior piece
     */
    public function isInterior(): bool
    {
        $numBorders = $this->countBorders();
        return $numBorders == 0;
    }

    /**
     * Count the number of borders in the puzzle piece
     * @return int The number of borders in the puzzle piece
     */
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
