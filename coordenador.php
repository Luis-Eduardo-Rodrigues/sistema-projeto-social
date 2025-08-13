<?php
include "protectc.php";
require "conn.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        echo $_SESSION["nome_coordenador"];
        
    ?>
    <a href="logout.php">sair</a>
</body>
</html>