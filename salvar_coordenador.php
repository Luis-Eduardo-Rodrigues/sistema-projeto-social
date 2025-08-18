<?php
$server = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pedemeia_crateus';

$mysqli = new mysqli($server, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo "<script>alert('Falha ao conectar ao banco: ".$mysqli->connect_error."'); window.history.back();</script>";
    exit;
}

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
    echo "<script>alert('Coordenador cadastrado com sucesso!'); window.location.href='criar_coordenador.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar coordenador: ".$stmt->error."'); window.history.back();</script>";
}

$stmt->close();
$mysqli->close();
?>
