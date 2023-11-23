<?php

use PHPUnit\Framework\TestCase;
use Puzzle\PuzzlePiece;

class PuzzlePieceTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $faces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $faces);

        $this->assertEquals(1, $piece->getId());
        $this->assertEquals($faces, $piece->getFaces());
    }

    public function testSetters()
    {
        $faces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $faces);

        $piece->setId(2);
        $piece->setFaces([5, 6, 7, 8]);

        $this->assertEquals(2, $piece->getId());
        $this->assertEquals([5, 6, 7, 8], $piece->getFaces());
    }

    public function testRotate()
    {
        $initialFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $initialFaces);

        $piece->rotate();

        $expectedFaces = [4, 1, 2, 3];
        $this->assertEquals($expectedFaces, $piece->getFaces());
    }

    public function testRotateToCorner()
    {
        $topLeftPattern = [0, 0, -1, -1];
        $testFaces = [1, 2, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("top-left");
        $this->assertTrue($this->checkPattern($piece, $topLeftPattern));

        $topRightPattern = [-1, 0, 0, -1];
        $testFaces = [1, 2, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("top-right");
        $this->assertTrue($this->checkPattern($piece, $topRightPattern));

        $bottomLeftPattern = [0, -1, -1, 0];
        $testFaces = [1, 2, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("bottom-left");
        $this->assertTrue($this->checkPattern($piece, $bottomLeftPattern));

        $bottomRightPattern = [-1, -1, 0, 0];
        $testFaces = [0, 1, 2, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("bottom-right");
        $this->assertTrue($this->checkPattern($piece, $bottomRightPattern));

        $topPattern = [0, 0, 0, -1];
        $testFaces = [1, 0, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("top");
        $this->assertTrue($this->checkPattern($piece, $topPattern));

        $bottomPattern = [0, -1, 0, 0];
        $testFaces = [1, 0, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("bottom");
        $this->assertTrue($this->checkPattern($piece, $bottomPattern));

        $leftPattern = [0, 0, -1, 0];
        $testFaces = [1, 0, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("left");
        $this->assertTrue($this->checkPattern($piece, $leftPattern));

        $rightPattern = [-1, 0, 0, 0];
        $testFaces = [0, 1, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToCorner("right");
        $this->assertTrue($this->checkPattern($piece, $rightPattern));
    }

    public function testRotateToEdge()
    {
        $leftPattern = [0, -1, -1, -1];
        $testFaces = [1, 2, 3, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToEdge("left");
        $this->assertTrue($this->checkPattern($piece, $leftPattern));

        $rightPattern = [-1, -1, 0, -1];
        $testFaces = [1, 2, 3, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToEdge("right");
        $this->assertTrue($this->checkPattern($piece, $rightPattern));

        $topPattern = [-1, 0, -1, -1];
        $testFaces = [1, 2, 3, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToEdge("top");
        $this->assertTrue($this->checkPattern($piece, $topPattern));

        $bottomPattern = [-1, -1, -1, 0];
        $testFaces = [1, 0, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $piece->rotateToEdge("bottom");
        $this->assertTrue($this->checkPattern($piece, $bottomPattern));
    }

    public function testIsCorner()
    {
        $testFaces = [0, 0, 1, 2];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertTrue($piece->isCorner());

        $testFaces = [0, 1, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isCorner());

        $testFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isCorner());

        $testFaces = [0, 0, 0, 1];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isCorner());
    }

    public function testIsLinearCorner()
    {
        $testFaces = [0, 1, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertTrue($piece->isLinearCorner());

        $testFaces = [0, 1, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isLinearCorner());

        $testFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isLinearCorner());

        $testFaces = [0, 0, 1, 2];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isLinearCorner());
    }

    public function testIsEdge()
    {
        $testFaces = [0, 1, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertTrue($piece->isEdge());

        $testFaces = [0, 1, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isEdge());

        $testFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isEdge());

        $testFaces = [0, 0, 1, 2];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isEdge());
    }

    public function testIsDoubleEdge()
    {
        $testFaces = [0, 1, 0, 2];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertTrue($piece->isDoubleEdge());

        $testFaces = [0, 1, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isDoubleEdge());

        $testFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isDoubleEdge());

        $testFaces = [0, 1, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isDoubleEdge());
    }

    public function testIsInterior()
    {
        $testFaces = [1, 2, 3, 4];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertTrue($piece->isInterior());

        $testFaces = [0, 1, 0, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isInterior());

        $testFaces = [1, 0, 3, 0];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isInterior());

        $testFaces = [0, 1, 2, 3];
        $piece = new PuzzlePiece(1, $testFaces);
        $this->assertFalse($piece->isInterior());
    }



    private function checkPattern(PuzzlePiece $piece, array $targetPattern): bool
    {
        $reflectionClass = new \ReflectionClass($piece);
        $reflectionMethod = $reflectionClass->getMethod('matchesPattern');
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($piece, $piece->getFaces(), $targetPattern);
    }
}
