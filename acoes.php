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

if(isset($_POST['adicionar_aluno'])) {
    $nome_aluno     = trim($_POST['nome_aluno'] ?? '');
    $cpf_aluno      = trim($_POST['cpf_aluno'] ?? '');
    $codigo_tecnico = trim($_POST['codigo_tecnico'] ?? '');
    $nome_escola    = trim($_POST['nome_escola'] ?? '');
    $endereco_aluno = trim($_POST['endereco_aluno'] ?? '');
    $ano            = trim($_POST['ano'] ?? '');

    // validações
    if (!preg_match('/^\d{11}$/', $cpf_aluno)) {
        echo "<script>alert('Erro: CPF deve conter exatamente 11 dígitos.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{4}$/', $ano)) {
        echo "<script>alert('Erro: Ano deve estar no formato YYYY (ex.: 2025).'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{6,10}$/', $codigo_tecnico)) {
        echo "<script>alert('Erro: Código Técnico deve ter entre 6 e 10 dígitos numéricos.'); window.history.back();</script>";
        exit;
    }

    // valida duplicidade do código técnico
    $verifica = $mysqli->prepare("SELECT COUNT(*) FROM aluno WHERE codigo_aluno = ?");
    $verifica->bind_param("s", $codigo_tecnico);
    $verifica->execute();
    $verifica->bind_result($count);
    $verifica->fetch();
    $verifica->close();

    if ($count > 0) {
        echo "<script>alert('Erro: Código Técnico já cadastrado!'); window.history.back();</script>";
        exit;
    }

    // consulta esfera da escola escolhida
    $stmt_escola = $mysqli->prepare("SELECT esfera FROM escola WHERE nome_escola = ?");
    $stmt_escola->bind_param("s", $nome_escola);
    $stmt_escola->execute();
    $stmt_escola->bind_result($esfera_escola);
    $stmt_escola->fetch();
    $stmt_escola->close();

    if(!$esfera_escola) {
        echo "<script>alert('Erro: Escola não encontrada na base de dados.'); window.history.back();</script>";
        exit;
    }

    // define valores de acordo com a esfera
    $esfera_aluno = $esfera_escola;
    $nome_escola_final = null;
    $nome_escola_medio = null;

    if(strtolower($esfera_escola) === 'municipal'){
        $nome_escola_final = $nome_escola;
    } else {
        $nome_escola_medio = $nome_escola;
    }

    // prepara inserção do aluno
    $stmt = $mysqli->prepare("INSERT INTO aluno 
        (nome_aluno, cpf_aluno, codigo_aluno, endereco_aluno, ano, esfera, nome_escola, nome_escola_medio) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo "<script>alert('Erro ao preparar consulta: " . $mysqli->error . "'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("ssssssss", 
        $nome_aluno, 
        $cpf_aluno, 
        $codigo_tecnico, 
        $endereco_aluno, 
        $ano, 
        $esfera_aluno, 
        $nome_escola_final, 
        $nome_escola_medio
    );

    if ($stmt->execute()) {
        // incrementa qtd_alunos na escola escolhida
        $update_escola = $mysqli->prepare("UPDATE escola SET qtd_alunos = qtd_alunos + 1 WHERE nome_escola = ?");
        $update_escola->bind_param("s", $nome_escola);
        $update_escola->execute();
        $update_escola->close();

        echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='adicionar_aluno.php';</script>";
    } else {
        if (strpos($stmt->error, 'Duplicate entry') !== false) {
            echo "<script>alert('Erro: Código Técnico ou CPF já cadastrado!'); window.history.back();</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar aluno: " . $stmt->error . "'); window.history.back();</script>";
        }
    }
}

if(isset($_POST['adicionar_escola'])) {
    if (isset($_POST['adicionar_escola'])) {
    $nome_escola     = trim($_POST['nome_escola'] ?? '');
    $endereco_escola = trim($_POST['endereco_escola'] ?? '');
    $esfera          = trim($_POST['esfera'] ?? '');

    // Verifica se já existe a escola cadastrada
    $check = $mysqli->prepare("SELECT id_escola FROM escola WHERE nome_escola = ? AND endereco_escola = ? AND esfera = ?");
    if (!$check) {
        echo "<script>alert('Erro ao preparar verificação: " . $mysqli->error . "'); window.history.back();</script>";
        exit;
    }

    $check->bind_param("sss", $nome_escola, $endereco_escola, $esfera);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Já existe escola cadastrada
        echo "<script>alert('Escola já cadastrada!'); window.location.href='adicionar_escola.php';</script>";
        exit;
    }
    $check->close();

    // prepara inserção da escola
    $stmt = $mysqli->prepare("INSERT INTO escola 
        (nome_escola, endereco_escola, esfera) 
        VALUES (?, ?, ?)");

    if (!$stmt) {
        echo "<script>alert('Erro ao preparar consulta: " . $mysqli->error . "'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("sss", 
        $nome_escola, 
        $endereco_escola, 
        $esfera
    );

    if ($stmt->execute()) {
        echo "<script>alert('Escola cadastrada com sucesso!'); window.location.href='adicionar_coordenador.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar escola: " . $stmt->error . "'); window.history.back();</script>";
    }
}
}

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

if(isset($_POST['update_escola'])){

    $id_escola = mysqli_real_escape_string($mysqli,$_POST['id_escola']);
    $endereco_escola = mysqli_real_escape_string($mysqli,trim($_POST['endereco_escola']));
    $nome_escola = mysqli_real_escape_string($mysqli,$_POST['nome_escola']);    

    $queryescola = "SELECT nome_escola, endereco_escola FROM escola WHERE id_escola = '$id_escola'";
    $queryescolaexec = $mysqli->query($queryescola);
    $escola = $queryescolaexec->fetch_assoc();
    $escolaantes = $escola["nome_escola"];
    $enderecoantes = $escola["endereco_escola"];

    if($escolaantes != $nome_escola) {
        $sql_update =  "UPDATE escola 
            SET nome_escola = '$nome_escola' 
            WHERE id_escola = '$id_escola'";

        $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);

        $sql_update =  "UPDATE aluno 
            SET nome_escola = '$nome_escola' 
            WHERE nome_escola = '$escolaantes'";

        $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);

        $sql_update =  "UPDATE usuario 
            SET nome_escola = '$nome_escola' 
            WHERE nome_escola = '$escolaantes'";

        $mysqli->query($sql_update) or die("Falha na execução do código SQL: " . $mysqli->error);

    }

    if ($enderecoantes != $endereco_escola) {
        $queryUpdate = "UPDATE escola SET endereco_escola = '$endereco_escola' WHERE id_escola = '$id_escola'" ;

        $consultaaluno = mysqli_query($mysqli, $queryUpdate);

    }

    if(mysqli_affected_rows($mysqli) > 0){
        $_SESSION["msgupescola"] = "Escola atualizada";
        header('Location: escola.php');
    }else{
            $_SESSION["msgupescola"] = "Escola não atualizada";
            header('Location: escola.php');
    }

    
}

if(isset($_POST['delete_escola'])){
    $id_escola = mysqli_real_escape_string($mysqli, $_POST['delete_escola']);
    $sql = "SELECT nome_escola FROM escola WHERE id_escola = '$id_escola'";

    $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
   
    $sqlDelete = mysqli_query($mysqli, "DELETE FROM escola WHERE id_escola = {$id_escola}")
    or die (mysqli_error($connection));

    if(mysqli_affected_rows($mysqli) > 0){

            $dados = $query->fetch_assoc(); 
            $escola = $dados['nome_escola'];

            $sql_aluno = "DELETE FROM aluno WHERE nome_escola = '$escola'";
            $query_escola = $mysqli->query($sql_aluno) or die( "". $mysqli->error);

            $_SESSION['message_escola'] = "Escola deletada";
                    
            header('Location: escola.php');
            exit;
        }else{
            $_SESSION['message_escola'] = "Escola não deletada";
            header('Location: escola.php');
            exit;
        }
    

}

if(isset($_POST['add_coordenador'])){

    $usuario       = trim($_POST['usuario'] ?? '');
    $senha         = trim($_POST['senha'] ?? '');
    $nome_escola   = trim($_POST['nome_escola'] ?? '');
    $cpf           = trim($_POST['cpf'] ?? '');
    $nome_usuario  = trim($_POST['nome_usuario'] ?? '');
    $cargo         = trim($_POST['cargo'] ?? '');

    if (!$usuario || !$senha || !$nome_escola || !$cpf || !$nome_usuario || !$cargo) {
        echo "<script>alert('Todos os campos são obrigatórios.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{11}$/', $cpf)) {
        echo "<script>alert('CPF deve ter 11 dígitos.'); window.history.back();</script>";
        exit;
    }

    $stmt = $mysqli->prepare("SELECT 1 FROM usuario WHERE usuario = ? OR cpf = ?");
    if (!$stmt) {
        echo "<script>alert('Erro ao preparar SELECT: ".$mysqli->error."'); window.history.back();</script>";
        exit;
    }
    $stmt->bind_param("ss", $usuario, $cpf);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Usuário ou CPF já cadastrado.'); window.history.back();</script>";
        $stmt->close();
        $mysqli->close();
        exit;
    }
    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("
        INSERT INTO usuario (usuario, senha, nome_escola, cpf, nome_usuario, cargo)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        echo "<script>alert('Erro ao preparar INSERT: ".$mysqli->error."'); window.history.back();</script>";
        exit;
    }

    $stmt->bind_param("ssssss", $usuario, $senha_hash, $nome_escola, $cpf, $nome_usuario, $cargo);

    if ($stmt->execute()) {
        echo "<script>alert('Coordenador cadastrado com sucesso!'); window.location.href='adicionar_coordenador.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar coordenador: ".$stmt->error."'); window.history.back();</script>";
    }

    $stmt->close();
    $mysqli->close();
}






?>