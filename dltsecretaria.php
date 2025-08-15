<?php
// Delete pro painel da secretaria
include "conn.php";

$id = $_GET["id_aluno"];

if(isset($_GET['id_aluno'])){
$sqlDelete = mysqli_query($mysqli, "DELETE FROM aluno WHERE id_aluno = {$id}")
or die (mysqli_error($connection));
// Quando o painel estiver pronto eu coloco no Location.
header('Location: ');
}
?>