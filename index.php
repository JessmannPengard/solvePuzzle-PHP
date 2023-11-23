<?php

require_once __DIR__ . '/autoload.php';

use Puzzle\Puzzle;
use Puzzle\PuzzleSolver;

$fileContents = "";
$errorMsg = "";

if (isset($_FILES["file"])) {
    $file = $_FILES["file"];

    if ($file["error"] === UPLOAD_ERR_OK) {
        $uploadedFile = $file["tmp_name"];

        $allowedExtensions = ["txt"];
        $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = $file["tmp_name"];
            $puzzle = Puzzle::loadPuzzle($file["tmp_name"]);
        } else {
            $errorMsg = "File extension not allowed (.txt)";
        }
    } else {
        $errorMsg = "Error uploading file: " . $file["error"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puzzle Solver</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/index.js"></script>
</head>

<body>

    <div id="spinner" class="spinner hidden"></div>

    <div id="content">
        <form action="" method="post" enctype="multipart/form-data" onsubmit="showSpinner()">
            <label for="file">Select puzzle file</label>
            <input type="file" name="file" id="file" accept=".txt">
            <input type="submit" value="Solve">
            <label for="error"><br><br><?= $errorMsg ?></label>
        </form>

        <?php

        if (isset($puzzle)) {
            $solver = new PuzzleSolver($puzzle);

            $startTime = microtime(true);
            $solver->solve();
            $endTime = microtime(true);

            $solutions = $solver->getSolutionsAsString(true);
            $executionTime = $endTime - $startTime;

            echo "<h3>Puzzle:</h3>";
            echo $puzzle->toString(true);
            echo "<h3>Solution(s):</h3>";
            echo $solutions;
            echo "Execution time: " . number_format($executionTime, 4) . " secs.";

            // Hide spinner
            echo '<script>hideSpinner();</script>';
        }

        ?>
    </div>
</body>

</html>