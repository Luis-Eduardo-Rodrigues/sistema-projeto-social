<?php
include("conn.php");
include("protects.php");

$sql = "SELECT * FROM escola";
$escolas = mysqli_query($mysqli, $sql);

$id_coord = $_GET['id'];
$sql = "SELECT * FROM usuario WHERE id_usuario = '$id_coord'";
$query = $mysqli->query($sql) or die();
$coord = $query->fetch_assoc();  

$opcescola = $escolas->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Coordenador - Sistema de Controle</title>
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
        <input type="hidden" name="id_coordenador" value="<?=$coord['id_usuario'];?>">

        <h2 class="text-3xl font-bold text-center">Editar Coordenador</h2>

        <div class="grid grid-cols-2 gap-10">
            <div class="flex flex-col gap-2">
                <label>Nome:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" value="<?=$coord['nome_usuario'];?>" name="nome_coordernador" required type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>CPF:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" value="<?=$coord['cpf'];?>" name="cpf_coordernador" required type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>Usuário:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" value="<?=$coord['usuario'];?>" name="usuario_coordernador" required type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>Senha:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="senha_coordernador" type="password">
            </div>
            <div class="flex flex-col gap-2 col-span-2">
                <label>Escola:</label>
                <select name="escola" id="escola" class="w-full px-4 py-2 rounded-md border border-gray-400">
                    <option selected value="<?=$coord['nome_escola'];?>"><?=$coord['nome_escola'];?></option>
                    <?php
                        

                        while($escola = $escolas->fetch_assoc()){
                    ?>
                        <option value="<?=$escola['nome_escola']?>"><?=$escola['nome_escola']?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-center gap-6">
            <button type="submit" name="update_coordenador" class="px-6 py-3 rounded-md bg-green-700 hover:bg-green-800 text-white font-bold">Salvar</button>
            <a href="secretaria.php" class="px-6 py-3 rounded-md bg-red-700 hover:bg-red-800 text-white font-bold">Voltar</a>
        </div>
    </form>
</body>
</html>