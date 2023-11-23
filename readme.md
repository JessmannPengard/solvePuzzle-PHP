# Puzzle Solver

Puzzle Solver is a PHP-based application that solves puzzles provided as text files. It includes both a command-line interface (`solve.php`) and a simple web interface (`index.php`). The application is designed to handle puzzles represented by a grid of pieces.

## Features

- Solve puzzles from text files
- Command-line interface
- Web interface for visual solving
- Unit tests for the Puzzle and PuzzlePiece classes

## Getting Started

### Prerequisites

- PHP 8.0 or later
- [Composer](https://getcomposer.org/) for dependency management

### Installation

Install dependencies:

    composer install


## Usage

### Command-Line interface:
    php solve.php path/to/puzzle.txt

### Web interface:
- Start a PHP built-in-server:
  
        php -S localhost:8000
- Open a web browser and go to `http://localhost:8000/index.php`
- Upload a puzzle file using the web interface and click **Solve**.

## File Format

The puzzle file should follow a specific format. The first line of the file specifies the dimensions of the puzzle (width and height), and each subsequent line represents a piece. The numbers on each line represent the faces of the piece. For example:

```console
3 2
1 2 3 4
4 3 2 1
1 2 3 4
4 3 2 1
1 2 3 4
4 3 2 1
```

In this example, the puzzle has a width of 3 and a height of 2. Each of the following lines represents a puzzle piece, with the numbers indicating the faces of the piece.


## Running Tests

To run the unit tests, use the following command:

    vendor/bin/phpunit