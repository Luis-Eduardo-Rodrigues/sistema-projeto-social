<?php
// Update pro pain Secretaria
session_start();
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

if(isset($_POST['update_aluno'])){

    $idaluno = mysqli_real_escape_string($mysqli,$_POST['aluno_id']);
    $nomealuno = mysqli_real_escape_string($mysqli,trim($_POST['nome_aluno']));
    $cpfaluno = mysqli_real_escape_string($mysqli,trim($_POST['cpf_aluno']));
    $codigoaluno = mysqli_real_escape_string($mysqli,trim($_POST['codigo_aluno']));
    $nomeescola = mysqli_real_escape_string($mysqli,$_POST['escola']);
    $med1 = mysqli_real_escape_string($mysqli,trim($_POST['media_1']));
    $med2 = mysqli_real_escape_string($mysqli,trim($_POST['media_2']));
    $med3 = mysqli_real_escape_string($mysqli,trim($_POST['media_3']));
    $med4 = mysqli_real_escape_string($mysqli,trim($_POST['media_4']));
    $f1 = mysqli_real_escape_string($mysqli,trim($_POST['frequencia_1']));
    $f2 = mysqli_real_escape_string($mysqli,trim($_POST['frequencia_2']));
    $f3 = mysqli_real_escape_string($mysqli,trim($_POST['frequencia_3']));
    $f4 = mysqli_real_escape_string($mysqli,trim($_POST['frequencia_4']));
    $a = mysqli_real_escape_string($mysqli,trim($_POST['ano_aluno']));

    $queryescola = "SELECT nome_escola FROM aluno WHERE id_aluno = '$idaluno'";
    $queryescolaexec = $mysqli->query($queryescola);
    $escola = $queryescolaexec->fetch_assoc();
    $escolaantes = $escola["nome_escola"];

    if($escolaantes != $nomeescola) {
        $sql_update =  "UPDATE escola 
            SET qtd_alunos = qtd_alunos + 1 
            WHERE nome_escola = '$nomeescola'";

            $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);

        $sql_update =  "UPDATE escola 
            SET qtd_alunos = qtd_alunos - 1 
            WHERE nome_escola = '$escolaantes'";

            $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);
    }

    $queryUpdate = "UPDATE aluno SET nome_aluno = '$nomealuno', cpf_aluno = '$cpfaluno', codigo_aluno = '$codigoaluno', nome_escola = '$nomeescola', media_1 = '$med1', media_2 = '$med2', media_3 = '$med3', media_4 = '$med4', frequencia_1 = '$f1', frequencia_2 = '$f2', frequencia_3 = '$f3', frequencia_4 = '$f4', ano = '$a' WHERE id_aluno = '$idaluno'   " ;

    $consultaaluno = mysqli_query($mysqli, $queryUpdate);

    if(mysqli_affected_rows($mysqli) > 0){
        $_SESSION["msgupaluno"] = "Aluno Atualizado";
        header('Location: aluno.php');
    }else{
        $_SESSION["msgupaluno"] = "Aluno não atualizado";
        header('Location: aluno.php');
    }
    
}

if(isset($_POST['pagamento_aluno'])){

    $id = mysqli_real_escape_string($mysqli, $_POST['pagamento_aluno']);
    $sql = "SELECT pagamento FROM aluno WHERE id_aluno = '$id'";
    $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
    $dados = $query->fetch_assoc();

    $pagamentoantes = $dados["pagamento"];
    $pagamento = $pagamentoantes + 1;

    $queryUpdate = "UPDATE aluno SET pagamento = '$pagamento' WHERE id_aluno = '$id'   " ;
    $consultaaluno = mysqli_query($mysqli, $queryUpdate);

    if(mysqli_affected_rows($mysqli) > 0){
        $_SESSION["msgupaluno"] = "Aluno Atualizado";
        header('Location: aluno.php');
    }else{
        $_SESSION["msgupaluno"] = "Aluno não atualizado";
        header('Location: aluno.php');
    }
    
}

if(isset($_POST['delete_aluno'])){
    $id = mysqli_real_escape_string($mysqli, $_POST['delete_aluno']);
    $sql = "SELECT * FROM aluno WHERE id_aluno = '$id'";
    $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
   
    $sqlDelete = mysqli_query($mysqli, "DELETE FROM aluno WHERE id_aluno = {$id}")
    or die (mysqli_error($connection));

    if(mysqli_affected_rows($mysqli) > 0){

            $dados = $query->fetch_assoc(); 
            $escola = $dados['nome_escola'];

            $sql_escola = "SELECT * FROM escola WHERE nome_escola = '$escola'";
            $query_escola = $mysqli->query($sql_escola) or die( "". $mysqli->error);

            $dados_escola = $query_escola->fetch_assoc();
            
            $qtdalunos = $dados_escola['qtd_alunos'];
            $qtd = $qtdalunos - 1;

            $sql_update =  "UPDATE escola 
            SET qtd_alunos = '$qtd' 
            WHERE nome_escola = '$escola'";

            $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);

            $_SESSION['message'] = "Aluno deletado";
                    
            header('Location: aluno.php');
            exit;
        }else{
            $_SESSION['message'] = "Aluno não deletado";
            header('Location: aluno.php');
            exit;
        }
    

}



?>