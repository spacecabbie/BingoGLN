<?php

declare(strict_types=1);

// Bootstrap the application
require_once __DIR__ . '/vendor/autoload.php';

use App\Src\BingoGenerator;
use App\Src\BingoPDF;

// Instantiate BingoGenerator with dependencies
$bingoGenerator = new BingoGenerator(new BingoPDF());

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bingoGenerator->handleRequest($_POST, $_FILES);
}

// Render the application
$renderData = $bingoGenerator->render();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bingo Card Generator</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/scripts.js" defer></script>
</head>
<body>
    <div class="container">
        <?php include __DIR__ . '/views/form.php'; ?>
        <?php include __DIR__ . '/views/preview.php'; ?>
    </div>
</body>
</html>
