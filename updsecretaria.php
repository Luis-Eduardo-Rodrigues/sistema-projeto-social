
<?php
// Update pro pain Secretaria
include "conn.php";

if(isset($_GET['id_aluno'])){
 $id = $_GET['id_aluno'];
 $sql = mysqli_query($mysqli, "SELECT * FROM aluno WHERE id_aluno = $id");
 $count = (is_array($sql))  ? count($sql) :1 ;


if($count){
 $e = mysqli_fetch_array($sql);
 $nomealuno = $e['nome_aluno'];
 $cpfaluno = $e['cpf_aluno'];
 $enderecoaluno = $e['numero_tecnico'];
 $nomeescola = $e['nome_escola'];
 $med1 = $e['media_1'];
 $med2 = $e['media_2'];
 $med3 = $e['media_3'];
 $med4 = $e['media_4'];
 $f1 = $e['frequencia_1'];
 $f2 = $e['frequencia_2'];
 $f3 = $e['frequencia_3'];
 $f4 = $e['frequencia_4'];
 $a = $e['ano'];
}
}

if(isset($_POST['editar'])){
    $id = $_GET['id_aluno'];
    $nomealuno = $_POST['nome_aluno'];
    $cpfaluno = $_POST['cpf_aluno'];
    $enderecoaluno = $_POST['numero_tecnico'];
    $nomeescola = $_POST['nome_escola'];
    $med1 = $_POST['media_1'];
    $med2 = $_POST['media_2'];
    $med3 = $_POST['media_3'];
    $med4 = $_POST['media_4'];
    $f1 = $_POST['frequencia_1'];
    $f2 = $_POST['frequencia_2'];
    $f3 = $_POST['frequencia_3'];
    $f4 = $_POST['frequencia_4'];
    $a = $_POST['ano'];
    $queryUpdate = "UPDATE aluno SET nome_aluno = '$nomealuno', cpf_aluno = '$cpfaluno', endereco_aluno = '$enderecoaluno', nome_escola = '$nomeescola', media_1 = '$med1', media_2 = '$med2', media_3 = '$med3', media_4 = '$med4', frequencia_1 = '$f1', frequencia_2 = '$f2', frequencia_3 = '$f3', frequencia_4 = '$f4', ano = '$a' WHERE id_aluno = '$id'   " ;

    $consultaaluno = mysqli_query($mysqli, $queryUpdate);
    // Quando o painel estiver pronto eu coloco no Location.
    header('Location: ');
}


?>