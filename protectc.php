<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if(!isset($_SESSION['nome_coordenador'])){
        die("Acesso recusado, loge para acessar. <p><a href=\"index.php\">Entrar</a></p>");
    }
?>