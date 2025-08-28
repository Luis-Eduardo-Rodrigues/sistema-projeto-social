<?php
    include('conn.php');
    include('protects.php');

    $id_aluno = (int)$_GET['id'];
    $sql = "
        SELECT a.*, e.esfera 
        FROM aluno a
        LEFT JOIN escola e 
            ON e.nome_escola = a.nome_escola 
            OR e.nome_escola = a.nome_escola_medio
        WHERE a.id_aluno = $id_aluno
        LIMIT 1
    ";
    $query = $mysqli->query($sql) or die("Erro ao buscar aluno: " . $mysqli->error);
    $aluno = $query->fetch_assoc();

    $esfera = strtolower($aluno['esfera'] ?? '');

    // Escola atual depende da esfera
    if ($esfera === 'municipal') {
        $escolaAtual = $aluno['nome_escola'] ?? '';
    } else {
        $escolaAtual = $aluno['nome_escola_medio'] ?? '';
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: Poppins, sans-serif; }
    </style>
</head>

<body class="bg-gray-50">

    <header class="mb-8">
        <img src="./src/header.png" class="w-full" />
    </header>

    <form action="acoes.php" method="POST" class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-12 flex flex-col gap-10">
        <input type="hidden" name="aluno_id" value="<?= $aluno['id_aluno']; ?>">

        <h2 class="text-3xl font-bold text-center">Editar Aluno</h2>

        <div class="grid grid-cols-2 gap-10">
            <div class="flex flex-col gap-2">
                <label>Nome:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" 
                       value="<?= htmlspecialchars($aluno['nome_aluno']) ?>" 
                       name="nome_aluno" type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>CPF:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" 
                       name="cpf_aluno" type="text" 
                       value="<?= htmlspecialchars($aluno['cpf_aluno']) ?>">
            </div>
            <div class="flex flex-col gap-2">
                <label>Ano:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" 
                       name="ano_aluno" type="text" 
                       value="<?= htmlspecialchars($aluno['ano']) ?>">
            </div>
            <div class="flex flex-col gap-2">
                <label>Código do Aluno:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" 
                       name="codigo_aluno" type="text" 
                       value="<?= htmlspecialchars($aluno['codigo_aluno']) ?>">
            </div>
            <div class="flex flex-col gap-2 col-span-2">
                <label>Escola:</label>
                <select name="escola" id="escola" class="w-full px-4 py-2 rounded-md border border-gray-400">
                    <?php
                        $sql = "SELECT * FROM escola";
                        $escolas = mysqli_query($mysqli, $sql);
                        foreach ($escolas as $e) {
                            $selected = ($e['nome_escola'] === $escolaAtual) ? 'selected' : '';
                            echo "<option value=\"".htmlspecialchars($e['nome_escola'])."\" $selected>"
                                .htmlspecialchars($e['nome_escola'])." (".htmlspecialchars($e['esfera']).")</option>";
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- NOTAS E FREQUÊNCIAS -->
        <h3 class="text-xl font-semibold text-center mt-6">Notas e Frequência</h3>

        <?php if ($esfera === 'municipal'): ?>
            <!-- Campos para rede municipal -->
            <div class="grid grid-cols-4 gap-6">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="flex flex-col gap-2">
                        <label>Média <?= $i; ?>:</label>
                        <input class="rounded-md px-4 py-2 border border-gray-400" 
                               value="<?= htmlspecialchars($aluno['media_'.$i.'_municipal']) ?>" 
                               name="media_<?= $i; ?>_municipal" type="text">
                    </div>
                <?php endfor; ?>

                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="flex flex-col gap-2">
                        <label>Frequência <?= $i; ?>:</label>
                        <input class="rounded-md px-4 py-2 border border-gray-400" 
                               value="<?= htmlspecialchars($aluno['frequencia_'.$i.'_municipal']) ?>" 
                               name="frequencia_<?= $i; ?>_municipal" type="text">
                    </div>
                <?php endfor; ?>
            </div>

            <?php if (!empty($aluno['media_1_9ano']) || !empty($aluno['frequencia_1_9ano'])): ?>
                <!-- Campos extras para 9º ano -->
                <h4 class="col-span-4 text-lg font-semibold mt-6">Notas e Frequência - 9º Ano</h4>
                <div class="grid grid-cols-4 gap-6">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="flex flex-col gap-2">
                            <label>Média <?= $i; ?> (9º ano):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="<?= htmlspecialchars($aluno['media_'.$i.'_9ano']) ?>" 
                                   name="media_<?= $i; ?>_9ano" type="text">
                        </div>
                    <?php endfor; ?>

                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="flex flex-col gap-2">
                            <label>Frequência <?= $i; ?> (9º ano):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="<?= htmlspecialchars($aluno['frequencia_'.$i.'_9ano']) ?>" 
                                   name="frequencia_<?= $i; ?>_9ano" type="text">
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Campos para estadual/federal -->
            <div class="grid grid-cols-4 gap-6">
                <?php for ($serie = 1; $serie <= 3; $serie++): ?>
                    <h4 class="col-span-4 text-lg font-semibold mt-4"><?= $serie; ?>ª Série do Ensino Médio</h4>

                    <?php for ($i = 1; $i <= 4; $i++): 
                        $col = "media_{$i}_medio{$serie}"; ?>
                        <div class="flex flex-col gap-2">
                            <label>Média <?= $i; ?> (<?= $serie; ?>º):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="<?= htmlspecialchars($aluno[$col]) ?>" 
                                   name="<?= $col ?>" type="text">
                        </div>
                    <?php endfor; ?>

                    <?php for ($i = 1; $i <= 4; $i++): 
                        $col = "frequencia_{$i}_medio{$serie}"; ?>
                        <div class="flex flex-col gap-2">
                            <label>Frequência <?= $i; ?> (<?= $serie; ?>º):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="<?= htmlspecialchars($aluno[$col]) ?>" 
                                   name="<?= $col ?>" type="text">
                        </div>
                    <?php endfor; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div class="flex items-center justify-center gap-6 mt-6">
            <button type="submit" name="update_aluno" 
                    class="px-6 py-3 rounded-md bg-green-700 hover:bg-green-800 text-white font-bold">
                Salvar
            </button>
            <a href="aluno.php" 
               class="px-6 py-3 rounded-md bg-red-700 hover:bg-red-800 text-white font-bold">
               Voltar
            </a>
        </div>
    </form>
</body>
</html>
