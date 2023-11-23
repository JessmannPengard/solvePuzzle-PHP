<?php

use PHPUnit\Framework\TestCase;
use Puzzle\Puzzle;
use Puzzle\PuzzlePiece;

class PuzzleTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $puzzle = new Puzzle();
        $puzzle->setCols(3);
        $puzzle->setRows(2);

        $this->assertEquals(3, $puzzle->getCols());
        $this->assertEquals(2, $puzzle->getRows());
        $this->assertEquals([], $puzzle->getPieces());
    }

    public function testSetters()
    {
        $puzzle = new Puzzle();
        $pieces = [new PuzzlePiece(1, [1, 2, 3, 4])];

        $puzzle->setCols(3);
        $puzzle->setRows(2);
        $puzzle->setPieces($pieces);

        $this->assertEquals(3, $puzzle->getCols());
        $this->assertEquals(2, $puzzle->getRows());
        $this->assertEquals($pieces, $puzzle->getPieces());
    }

    public function testIsOneDimensional()
    {
        $puzzle1DX = new Puzzle();
        $puzzle1DX->setCols(1);
        $puzzle1DX->setRows(3);

        $puzzle1DY = new Puzzle();
        $puzzle1DY->setCols(3);
        $puzzle1DY->setRows(1);

        $puzzle2D = new Puzzle();
        $puzzle2D->setCols(4);
        $puzzle2D->setRows(4);

        $this->assertTrue($puzzle1DX->isOneDimensional());
        $this->assertTrue($puzzle1DY->isOneDimensional());
        $this->assertFalse($puzzle2D->isOneDimensional());
    }
}
