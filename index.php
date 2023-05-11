<?php require 'main.php' ?>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <?='<pre>'?>
        <?=var_dump($pdo->select("SELECT * FROM persosn"));?>
    </body>
</html>