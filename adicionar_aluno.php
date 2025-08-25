<?php
    include('conn.php');
    include('protects.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Aluno - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <header class="mb-8">
        <img src="./src/header.png" class="w-full" />
    </header>

    <form action="acoes.php" method="POST" class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-12 flex flex-col gap-10">
        <input type="hidden" name="aluno_id">

        <h2 class="text-3xl font-bold text-center">Adicionar Aluno</h2>

        <div class="grid grid-cols-2 gap-10">
            <div class="flex flex-col gap-2">
                <label>Nome:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="nome_aluno" type="text" maxlength="50" required>
            </div>
            <div class="flex flex-col gap-2">
                <label>CPF:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="cpf_aluno" type="text" inputmode="numeric" pattern="\d{11}" maxlength="11" required>
            </div>
            <div class="flex flex-col gap-2">
                <label>Ano:</label>
                <input pattern="\d{4}" maxlength="4" required class="w-full rounded-md px-4 py-2 border border-gray-400" name="ano" type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>Código do Aluno:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="codigo_tecnico" type="text" pattern="\d{6,10}" minlength="6" maxlength="10" required>
            </div>
            <div class="flex flex-col gap-2 col-span-2">
                <label>Escola:</label>
                <select name="nome_escola" required id="escola" class="w-full px-4 py-2 rounded-md border border-gray-400">
                    <option selected>Escolha sua escolha</option>
                    <?php
                        $sql = "SELECT * FROM escola";
                        $escolas = mysqli_query($mysqli, $sql);
                        foreach($escolas as $escola){
                    ?>
                        <option value="<?=$escola['nome_escola']?>"><?=$escola['nome_escola']?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-center gap-6 mt-6">
            <button type="submit" name="adicionar_aluno" class="px-6 py-3 rounded-md bg-green-700 hover:bg-green-800 text-white font-bold">Salvar</button>
            <a href="aluno.php" class="px-6 py-3 rounded-md bg-red-700 hover:bg-red-800 text-white font-bold">Voltar</a>
        </div>
    </form>
</body>
</html>
