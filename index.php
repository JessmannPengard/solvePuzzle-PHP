<?php

require "helpers/functions.php";
require "classes/Puzzle.php";
require "classes/PuzzlePiece.php";
require "classes/PuzzleSolver.php";

$fileContents = "";
$errorMsg = "";
if (isset($_FILES["file"])) {
    $file = $_FILES["file"];

    if ($file["error"] === UPLOAD_ERR_OK) {
        $uploadedFile = $file["tmp_name"];

        $allowedExtensions = ["txt"];
        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $fileContents = file_get_contents($uploadedFile);

            //$escapedFileContents = htmlentities($fileContents, ENT_QUOTES, 'UTF-8');
            //echo "<pre>" . $escapedFileContents . "</pre>";

        } else {
            $errorMsg = "File extension not allowed (.txt)";
        }
    } else {
        $errorMsg = "Error uploading file: " . $file["error"];
    }
}

function solvePuzzle($fileContents)
{

    $puzzle = new Puzzle($fileContents);

    $solver = new PuzzleSolver($puzzle);

    $solver->solve();

    $solutions = $solver->getSolutions();

    return $solutions;
}

function paintSolution($solution)
{
    foreach ($solution as $row) {
        foreach ($row as $piece) {
            echo $piece->getId();
            echo " ";
        }
        echo "<br>";
    }
    echo "<br>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Solver</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="file">Select puzzle file</label>
        <input type="file" name="file" id="file" accept=".txt">
        <input type="submit" value="Solve">
        <label for="error"><br><br><?= $errorMsg ?></label>
    </form>

    <?php

    if ($fileContents != "") {
        $startTime = microtime(true);

        $solutions = solvePuzzle($fileContents);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        echo "<h2>Solution(s):</h2>";
        foreach ($solutions as $solution) {
            paintSolution($solution);
        }
        echo "Execution time: " . number_format($executionTime, 4) . " secs.";
    }

    ?>

</body>

</html>