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

if (isset($_POST['update_aluno'])) {
    $idaluno      = mysqli_real_escape_string($mysqli, $_POST['aluno_id']);
    $nomealuno    = mysqli_real_escape_string($mysqli, trim($_POST['nome_aluno']));
    $cpfaluno     = mysqli_real_escape_string($mysqli, trim($_POST['cpf_aluno']));
    $codigoaluno  = mysqli_real_escape_string($mysqli, trim($_POST['codigo_aluno']));
    $nomeescola   = mysqli_real_escape_string($mysqli, $_POST['escola']);
    $ano          = mysqli_real_escape_string($mysqli, trim($_POST['ano_aluno']));

    $sqlEsfera = "SELECT esfera FROM escola WHERE nome_escola = '$nomeescola' LIMIT 1";
    $resEsfera = $mysqli->query($sqlEsfera);
    $dadosEsfera = $resEsfera->fetch_assoc();
    $esfera = strtolower($dadosEsfera['esfera']);

    if ($esfera === 'municipal') {
        $med1 = mysqli_real_escape_string($mysqli, trim($_POST['media_1_municipal']));
        $med2 = mysqli_real_escape_string($mysqli, trim($_POST['media_2_municipal']));
        $med3 = mysqli_real_escape_string($mysqli, trim($_POST['media_3_municipal']));
        $med4 = mysqli_real_escape_string($mysqli, trim($_POST['media_4_municipal']));
        $f1   = mysqli_real_escape_string($mysqli, trim($_POST['frequencia_1_municipal']));
        $f2   = mysqli_real_escape_string($mysqli, trim($_POST['frequencia_2_municipal']));
        $f3   = mysqli_real_escape_string($mysqli, trim($_POST['frequencia_3_municipal']));
        $f4   = mysqli_real_escape_string($mysqli, trim($_POST['frequencia_4_municipal']));

        $queryUpdate = "
            UPDATE aluno SET 
                nome_aluno = '$nomealuno',
                cpf_aluno = '$cpfaluno',
                codigo_aluno = '$codigoaluno',
                nome_escola = '$nomeescola',
                ano = '$ano',
                media_1_municipal = '$med1',
                media_2_municipal = '$med2',
                media_3_municipal = '$med3',
                media_4_municipal = '$med4',
                frequencia_1_municipal = '$f1',
                frequencia_2_municipal = '$f2',
                frequencia_3_municipal = '$f3',
                frequencia_4_municipal = '$f4'
            WHERE id_aluno = '$idaluno'
        ";

    } else {
        $updates = [];
        for ($serie = 1; $serie <= 3; $serie++) {
            for ($i = 1; $i <= 4; $i++) {
                $m = mysqli_real_escape_string($mysqli, trim($_POST["media_{$i}_medio{$serie}"] ?? 0));
                $f = mysqli_real_escape_string($mysqli, trim($_POST["frequencia_{$i}_medio{$serie}"] ?? 0));
                $updates[] = "media_{$i}_medio{$serie} = '$m'";
                $updates[] = "frequencia_{$i}_medio{$serie} = '$f'";
            }
        }

        $updatesStr = implode(", ", $updates);

        $queryUpdate = "
            UPDATE aluno SET 
                nome_aluno = '$nomealuno',
                cpf_aluno = '$cpfaluno',
                codigo_aluno = '$codigoaluno',
                nome_escola_medio = '$nomeescola',
                ano = '$ano',
                $updatesStr
            WHERE id_aluno = '$idaluno'
        ";
    }

    $consultaaluno = mysqli_query($mysqli, $queryUpdate);

    if (mysqli_affected_rows($mysqli) > 0) {
        $_SESSION["msgupaluno"] = "Aluno Atualizado";
    } else {
        $_SESSION["msgupaluno"] = "Aluno não atualizado";
    }
    header('Location: aluno.php');
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

if (isset($_POST['adicionar_aluno'])) {
    $nome_aluno     = trim($_POST['nome_aluno'] ?? '');
    $cpf_aluno      = trim($_POST['cpf_aluno'] ?? '');
    $codigo_tecnico = trim($_POST['codigo_tecnico'] ?? '');
    $nome_escola    = trim($_POST['nome_escola'] ?? '');
    $endereco_aluno = trim($_POST['endereco_aluno'] ?? '');
    $ano            = trim($_POST['ano'] ?? '');

    // ----------------- VALIDAÇÕES -----------------
    if (empty($nome_aluno) || empty($cpf_aluno) || empty($codigo_tecnico) || empty($nome_escola) || empty($ano)) {
        echo "<script>alert('Erro: Preencha todos os campos.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{11}$/', $cpf_aluno)) {
        echo "<script>alert('Erro: CPF deve conter exatamente 11 dígitos.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{4}$/', $ano)) {
        echo "<script>alert('Erro: Ano deve estar no formato YYYY.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^\d{6,10}$/', $codigo_tecnico)) {
        echo "<script>alert('Erro: Código Técnico deve ter entre 6 e 10 dígitos numéricos.'); window.history.back();</script>";
        exit;
    }

// ----------------- DUPLICIDADE -----------------
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM aluno WHERE cpf_aluno = ? OR codigo_aluno = ?");
$stmt->bind_param("ss", $cpf_aluno, $codigo_tecnico);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo "<script>alert('Erro: CPF ou Código já cadastrado!'); window.history.back();</script>";
    exit;
}

// ----------------- CONSULTA ESCOLA -----------------
$stmt_escola = $mysqli->prepare("SELECT esfera FROM escola WHERE nome_escola = ?");
$stmt_escola->bind_param("s", $nome_escola);
$stmt_escola->execute();
$stmt_escola->bind_result($esfera_escola);
$stmt_escola->fetch();
$stmt_escola->close();

if (!$esfera_escola) {
    echo "<script>alert('Erro: Escola não encontrada.'); window.history.back();</script>";
    exit;
}

// ----------------- DEFINIÇÕES POR ESFERA -----------------
$esfera_escola_lower = strtolower($esfera_escola);

// Zerar notas/frequências municipais e médias
$media_1_municipal = 0;
$media_2_municipal = 0;
$media_3_municipal = 0;
$media_4_municipal = 0;
$frequencia_1_municipal = 0;
$frequencia_2_municipal = 0;
$frequencia_3_municipal = 0;
$frequencia_4_municipal = 0;

$media_1_medio1 = 0.0; $media_2_medio1 = 0.0; $media_3_medio1 = 0.0; $media_4_medio1 = 0.0;
$media_1_medio2 = 0.0; $media_2_medio2 = 0.0; $media_3_medio2 = 0.0; $media_4_medio2 = 0.0;
$media_1_medio3 = 0.0; $media_2_medio3 = 0.0; $media_3_medio3 = 0.0; $media_4_medio3 = 0.0;

$frequencia_1_medio1 = 0.0; $frequencia_2_medio1 = 0.0; $frequencia_3_medio1 = 0.0; $frequencia_4_medio1 = 0.0;
$frequencia_1_medio2 = 0.0; $frequencia_2_medio2 = 0.0; $frequencia_3_medio2 = 0.0; $frequencia_4_medio2 = 0.0;
$frequencia_1_medio3 = 0.0; $frequencia_2_medio3 = 0.0; $frequencia_3_medio3 = 0.0; $frequencia_4_medio3 = 0.0;

// Campos padrão
$nome_escola_municipal = '';
$nome_escola_medio = NULL;
$ano_fundamental = NULL; // Para Municipal
$ano_medio = NULL;       // Para Estadual/Federal
$pagamento = NULL;
$serie = NULL;

// Ajuste conforme esfera
if ($esfera_escola_lower === 'municipal') {
    $nome_escola_municipal = $nome_escola;
    $ano_fundamental = $ano;
} elseif ($esfera_escola_lower === 'estadual' || $esfera_escola_lower === 'federal') {
    $nome_escola_municipal = ''; // coluna NOT NULL
    $nome_escola_medio = $nome_escola;
    $ano_medio = $ano;
} else {
    echo "<script>alert('Erro: Esfera da escola inválida.'); window.history.back();</script>";
    exit;
}

// ----------------- INSERÇÃO COM TRANSAÇÃO -----------------
$mysqli->begin_transaction();

$sql = "INSERT INTO aluno
    (nome_aluno, cpf_aluno, codigo_aluno, endereco_aluno, nome_escola,
     media_1_municipal, media_2_municipal, media_3_municipal, media_4_municipal,
     frequencia_1_municipal, frequencia_2_municipal, frequencia_3_municipal, frequencia_4_municipal,
     ano,
     media_1_medio1, media_2_medio1, media_3_medio1, media_4_medio1,
     media_1_medio2, media_2_medio2, media_3_medio2, media_4_medio2,
     media_1_medio3, media_2_medio3, media_3_medio3, media_4_medio3,
     frequencia_1_medio1, frequencia_2_medio1, frequencia_3_medio1, frequencia_4_medio1,
     frequencia_1_medio2, frequencia_2_medio2, frequencia_3_medio2, frequencia_4_medio2,
     frequencia_1_medio3, frequencia_2_medio3, frequencia_3_medio3, frequencia_4_medio3,
     ano_medio, pagamento, nome_escola_medio, esfera, serie)
    VALUES (?,?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,
            ?,
            ?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,
            ?,?,?,?,?)";

$stmt_insert = $mysqli->prepare($sql);
if (!$stmt_insert) {
    $mysqli->rollback();
    echo "<script>alert('Erro ao preparar INSERT: ".$mysqli->error."'); window.history.back();</script>";
    exit;
}

$stmt_insert->bind_param(
    "sssss" . "iiiiiiii" . "s" . str_repeat("d", 12) . str_repeat("d", 12) . "siss" . "i",
    $nome_aluno,
    $cpf_aluno,
    $codigo_tecnico,
    $endereco_aluno,
    $nome_escola_municipal,

    $media_1_municipal,
    $media_2_municipal,
    $media_3_municipal,
    $media_4_municipal,
    $frequencia_1_municipal,
    $frequencia_2_municipal,
    $frequencia_3_municipal,
    $frequencia_4_municipal,

    $ano_fundamental, // só tem valor se for municipal

    $media_1_medio1, $media_2_medio1, $media_3_medio1, $media_4_medio1,
    $media_1_medio2, $media_2_medio2, $media_3_medio2, $media_4_medio2,
    $media_1_medio3, $media_2_medio3, $media_3_medio3, $media_4_medio3,

    $frequencia_1_medio1, $frequencia_2_medio1, $frequencia_3_medio1, $frequencia_4_medio1,
    $frequencia_1_medio2, $frequencia_2_medio2, $frequencia_3_medio2, $frequencia_4_medio2,
    $frequencia_1_medio3, $frequencia_2_medio3, $frequencia_3_medio3, $frequencia_4_medio3,

    $ano_medio, // só tem valor se for estadual/federal
    $pagamento,
    $nome_escola_medio,
    $esfera_escola,
    $serie
);

if (!$stmt_insert->execute()) {
    $err = $stmt_insert->error;
    $stmt_insert->close();
    $mysqli->rollback();
    echo "<script>alert('Erro ao cadastrar aluno: ".htmlspecialchars($err, ENT_QUOTES)."'); window.history.back();</script>";
    exit;
}
$stmt_insert->close();

// Atualiza qtd_alunos
$stmt_update = $mysqli->prepare("UPDATE escola SET qtd_alunos = IFNULL(qtd_alunos,0) + 1 WHERE nome_escola = ?");
$stmt_update->bind_param("s", $nome_escola);
$stmt_update->execute();
$stmt_update->close();

$mysqli->commit();

echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='adicionar_aluno.php';</script>";


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

if (isset($_POST['update_coordenador'])) {

    $idcoord    = mysqli_real_escape_string($mysqli, $_POST['id_coordenador']);
    $nomecoord  = mysqli_real_escape_string($mysqli, trim($_POST['nome_coordernador']));
    $usuariocoord  = mysqli_real_escape_string($mysqli, trim($_POST['usuario_coordernador']));
    $cpfcoord   = mysqli_real_escape_string($mysqli, trim($_POST['cpf_coordernador']));
    $nomeescola = mysqli_real_escape_string($mysqli, $_POST['escola']);
    $senhacoord = mysqli_real_escape_string($mysqli, trim($_POST['senha_coordernador']));

    $query = "SELECT nome_escola FROM usuario WHERE id_usuario = '$idcoord'";
    $res   = $mysqli->query($query);
    $dados = $res->fetch_assoc();
    $escolaantes = $dados['nome_escola'];

    $sql = "UPDATE usuario 
    SET nome_usuario = '$nomecoord', cpf = '$cpfcoord', usuario = '$usuariocoord', nome_escola = '$nomeescola'";
    
    if (!empty($senhacoord)) {
        $sql .= ", senha='" . password_hash($senhacoord, PASSWORD_DEFAULT) . "'";
    }
    
    $sql .= " WHERE id_usuario = '$idcoord'";
    mysqli_query($mysqli, $sql);

    if (mysqli_affected_rows($mysqli) > 0) {
        $_SESSION["msgupcoord"] = "Coordenador atualizado com sucesso";
        header('Location: coordenadores.php');
    } else {
        $_SESSION["msgupcoord"] = "Nenhuma alteração feita";
        header('Location: coordenadores.php');
    }
}

if(isset($_POST['delete_coord'])){
    $id_coord = mysqli_real_escape_string($mysqli, $_POST['delete_coord']);
    $sql = "SELECT nome_escola FROM escola WHERE id_escola = '$id_escola'";

    $query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
   
    $sqlDelete = mysqli_query($mysqli, "DELETE FROM usuario WHERE id_usuario = {$id_coord}")
    or die (mysqli_error($connection));

    if(mysqli_affected_rows($mysqli) > 0){
        $_SESSION['message_coord'] = "Coordenador deletado";            
        header('Location: coordenadores.php');
        exit;
    }else{
        $_SESSION['message_coord'] = "Coordenador não deletada";
        header('Location: coordenadores.php');
        exit;
    }
    

}







?>