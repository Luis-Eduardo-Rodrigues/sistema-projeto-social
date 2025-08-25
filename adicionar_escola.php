<?php
    include('conn.php');
    include('protects.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Escola - Sistema de Controle</title>
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
        <h2 class="text-3xl font-bold text-center">Adicionar Escola</h2>

        <div class="grid grid-cols-2 gap-10">
            <div class="flex flex-col gap-2">
                <label>Nome:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="nome_escola" type="text" required>
            </div>
            <div class="flex flex-col gap-2">
                <label>Endereço:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="endereco_escola" type="text" required>
            </div>
            <div class="flex flex-col gap-2">
                <label>Esfera:</label>
                <select name="esfera" id="escola" class="w-full px-4 py-2 rounded-md border border-gray-400">
                    <option selected value="Selecione a esfera"></option>
                    <option value="Municipal">Municipal</option>
                    <option value="Estadual">Estadual</option>
                    <option value="Federal">Federal</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-center gap-6 mt-6">
            <button type="submit" name="adicionar_escola" class="px-6 py-3 rounded-md bg-green-700 hover:bg-green-800 text-white font-bold">Salvar</button>
            <a href="escola.php" class="px-6 py-3 rounded-md bg-red-700 hover:bg-red-800 text-white font-bold">Voltar</a>
        </div>
    </form>
</body>
</html>
