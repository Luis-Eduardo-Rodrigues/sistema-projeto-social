<?php
include("conn.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins
        }
    </style>
</head>
<body>
    <?php
        $id_aluno = $_GET['id'];
        $sql = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
        $query = $mysqli->query($sql) or die();
    ?>
    <form action="" method="POST" class="flex flex-col gap-6 p-12 text-center">
        <div>
            <h2 class="text-3xl font-bold">Editar Aluno</h2>
        </div>
        <div>
            <input class="w-72 rounded-md px-4 py-2 border border-black border-1" type="text" placeholder="<?= $aluno['nome_aluno'] ?>">
        </div>
        <div>
            <input class="w-72 rounded-md px-4 py-2 border border-black border-1" type="text" placeholder="<?= $aluno['cpf_aluno'] ?>">
        </div>
        <div>
            <input class="w-72 rounded-md px-4 py-2 border border-black border-1" type="text" placeholder="Média">
        </div>
        <div>
            <input class="w-72 rounded-md px-4 py-2 border border-black border-1" type="text" placeholder="Frequencia">
        </div>
        <div class="">
            <button type="submit" class="px-4 py-2 rounded-md bg-green-800 text-white font-bold">Salvar</button>
            <a href="aluno.php" class="px-4 py-2 bg-red-800 text-white rounded-md font-bold">Voltar</a>
        </div>
    </form>
</body>
</html>