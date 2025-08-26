<?php
include "conn.php";

if (isset($_GET['id_escola'])) {
    $id = (int)$_GET['id_escola']; 


    mysqli_begin_transaction($mysqli);


    $sql1 = "DELETE FROM aluno WHERE id_escola = $id";
    $result1 = mysqli_query($mysqli, $sql1);

    if (!$result1) {
        mysqli_rollback($mysqli);
        die("Erro ao deletar alunos: " . mysqli_error($mysqli));
    }

 
    $sql2 = "DELETE FROM escola WHERE id_escola = $id";
    $result2 = mysqli_query($mysqli, $sql2);

    if (!$result2) {
        mysqli_rollback($mysqli);
        die("Erro ao deletar escola: " . mysqli_error($mysqli));
    }

   
    mysqli_commit($mysqli);

    
    header("Location:");
    exit;
}
?>
