<?php require 'main.php' ?>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <?='<pre>'?>
        <?php 
        $params = array(
            'id'=>2
        );
        var_dump($klev->select('SELECT * FROM person Where pId = :id',$params));
        ?>        
    </body>
</html>