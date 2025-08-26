<?php
include "conn.php";

if(isset($_GET['id_escola'])){
$id = $_GET['id_escola'];
$sql = mysqli_query($mysqli, "SELECT * FROM escola WHERE id_escola = $id");
$count = (is_array($sql)) ? count($sql) :1 ; 

if($count){
$e = mysqli_fetch_array($sql);
$nomeescola = $e['nome_escola'];
$enderecoescola = $e['endereco_escola'];

}
}

if(isset($_POST['editar'])){
$id = $_GET['id_escola'];
$nomeescola = $_GET['nome_escola'];
$enderecoescola = $_GET['endereco_escola'];

$queryUpdate = "UPDATE escola SET nome_escola = '$nomeescola', endereco_escola = '$enderecoescola' WHERE id_escola = '$id'";

$consulta1 = mysqli_query($mysqli, $queryUpdate);

$queryUpdate2 = "UPDATE aluno SET nome_escola = '$nomeescola' WHERE id_aluno = '$id' ";

$consulta2 = mysqli_query($mysqli, $queryUpdate2);

header('Location:');
}
?>