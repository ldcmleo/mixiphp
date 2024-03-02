<?php use core\routing\Router; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MixiPHP</title>
</head>
<body>
    <h1>Welcome to MixiPHP!</h1>
    <p>A basic lightwheight php framework with MVC architecture.</p>
    <p>You have MixiPHP installed sucessfully!</p>
    <a href="<?= Router::go("varExample", ["MixiPHP"]) ?>">Example of Router::GO</a>
</body>
</html>
