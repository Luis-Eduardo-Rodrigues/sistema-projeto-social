<?php
$server = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pedemeia_crateus';

$mysqli = new mysqli($server, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo "<script>alert('Falha ao conectar ao banco: " . $mysqli->connect_error . "'); window.history.back();</script>";
    exit;
}

$nome_aluno     = trim($_POST['nome_aluno'] ?? '');
$cpf_aluno      = trim($_POST['cpf_aluno'] ?? '');
$numero_tecnico = trim($_POST['codigo_tecnico'] ?? '');
$nome_escola    = trim($_POST['nome_escola'] ?? '');

$ano            = trim($_POST['ano'] ?? '');

if (!preg_match('/^\d{11}$/', $cpf_aluno)) {
    echo "<script>alert('Erro: CPF deve conter exatamente 11 dígitos.'); window.history.back();</script>";
    exit;
}

if (!preg_match('/^\d{4}$/', $ano)) {
    echo "<script>alert('Erro: Ano deve estar no formato YYYY (ex.: 2025).'); window.history.back();</script>";
    exit;
}

if (!preg_match('/^\d{6,10}$/', $numero_tecnico)) {
    echo "<script>alert('Erro: Código Técnico deve ter entre 6 e 10 dígitos numéricos.'); window.history.back();</script>";
    exit;
}

$verifica = $mysqli->prepare("SELECT COUNT(*) FROM aluno WHERE codigo_tecnico = ?");
$verifica->bind_param("s", $numero_tecnico);
$verifica->execute();
$verifica->bind_result($count);
$verifica->fetch();
$verifica->close();

if ($count > 0) {
    echo "<script>alert('Erro: Código Técnico já cadastrado!'); window.history.back();</script>";
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO aluno (nome_aluno, cpf_aluno, codigo_tecnico, nome_escola, ano) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    echo "<script>alert('Erro ao preparar consulta: " . $mysqli->error . "'); window.history.back();</script>";
    exit;
}

$stmt->bind_param("sssss", $nome_aluno, $cpf_aluno, $numero_tecnico, $nome_escola, $ano);

// 3. Executa e trata erros
if ($stmt->execute()) {
    echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='criar_aluno.php';</script>";
} else {
    // Caso algum erro de duplicidade ocorra mesmo assim
    if (strpos($stmt->error, 'Duplicate entry') !== false) {
        echo "<script>alert('Erro: Código Técnico ou CPF já cadastrado!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar aluno: " . $stmt->error . "'); window.history.back();</script>";
    }
}

// Fecha conexões
$stmt->close();
$mysqli->close();
?>
