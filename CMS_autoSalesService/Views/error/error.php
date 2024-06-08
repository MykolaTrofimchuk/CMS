<?php
/** @var string $errorCode */

if (!isset($errorCode))
    $errorCode = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $errorCode ?></title>
</head>
<body>
<div class="modal-content rounded-4 shadow">
    <div class="modal-body p-5 pt-0">
        <div class="alert alert-danger" role="alert">
            <h1>Error <?= $errorCode ?></h1>
            <p>Sorry, the page you are looking for does not exist.</p>
        </div>
    </div>
</div>
</body>
</html>
