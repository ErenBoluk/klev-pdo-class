<?php require 'main.php' ?>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <?='<pre>'?>
        <?php // $pdo->select("SELECT * FROM persosn");?>
        <?php
          function getTableName($query) {
            if (stripos($query, 'update') === 0) {
                // UPDATE sorgusu için tablo adını ayır
                $pattern = '/\bUPDATE\s+\`?(\w+)\`?/i';
            } else {
                // Diğer sorgu türleri için FROM ifadesinden sonra gelen ilk kelimeyi ayır
                $pattern = '/\bFROM\s+\`?(\w+)\`?/i';
            }
            preg_match($pattern, $query, $matches);
            return isset($matches[1]) ? $matches[1] : null;
        }
        var_dump(getTableName("SELECT * FROM selectperson"));
        echo '<hr>';
        var_dump(getTableName("Update updateperson SET name = 'Klev' WHERE id = 1"));
        echo '<hr>';
        var_dump(getTableName('DELETE FROM `deleteperson` WHERE id = 1'));

        ?>
    </body>
</html>