<?php
/** @var string $Title */
/** @var string $Content */

if(empty($Title))
    $Title = '';
if(empty($Content))
    $Content = '';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$Title ?></title>
</head>
<body>
<header style="background-color: yellow">
    <div>Header</div>
</header>
<div>
    <h1><?=$Title ?></h1>
    <h1><?=$Content ?></h1>
</div>
<footer style="background-color: orange">
    <div>Footer</div>
</footer>
</body>
</html>
