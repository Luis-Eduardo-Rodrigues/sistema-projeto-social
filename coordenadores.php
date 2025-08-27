<?php
include("conn.php");
include("protects.php");

    if(isset($_SESSION['msgupcoord'])){
        if($_SESSION['msgupcoord'] != ""){
            echo "<script>alert('{$_SESSION['msgupcoord']}')</script>";
            $_SESSION['msgupcoord'] = "";
        }
    }

    if(isset($_SESSION['message_coord'])){
        if($_SESSION['message_coord'] != ""){
            echo "<script>alert('{$_SESSION['message_coord']}')</script>";
            $_SESSION['message_coord'] = "";
        }
    }
   
    $query_count_coord = "SELECT COUNT(*) FROM usuario WHERE cargo = 'Coordenador'";
    $query_count_coord_exec = $mysqli->query($query_count_coord) or die($mysqli->error);
    $sql_count_coord = $query_count_coord_exec->fetch_assoc();
    $count_coord = $sql_count_coord['COUNT(*)'];

    $pagina = 0;
    
    if(!isset($_GET['pagina'])){
        $pagina = 1;
    }else{
        $pagina = $_GET['pagina'] ? intval($_GET['pagina']) : 1;
    }
    
    $limit = 10;
    $offset = ($pagina - 1) * $limit;

    $numero_pagina = ceil($count_coord / $limit);


    $query_coord = "SELECT * FROM usuario WHERE cargo = 'Coordenador' ORDER BY nome_usuario ASC LIMIT {$limit} OFFSET {$offset}";
    $query_coord_exec = $mysqli->query($query_coord) or die($mysqli->error);
    
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Secretaria - Sistema de Controle</title>
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
    <header class="h-40 flex items-center relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho">
    </header>

    <main class="my-12 flex items-center justify-center flex-col gap-6">

        <section class="flex justify-center mt-6 w-[70%]">
            <div class="w-[100%] border-2 border-green-800 overflow-hidden rounded-lg shadow-lg">
                <div>
                    <table class="w-[100%] border-collapse text-lg">
                        <thead>
                            <tr class="border-b border-green-600 bg-green-100">
                                <th class="p-4 text-left">NOME</th>
                                <th class="p-4 text-left">CPF</th>
                                <th class="p-4 text-left">ESCOLA</th>
                                <th class="p-4 text-left">USUÁRIO</th>
                                <th class="p-4 text-center">AÇÕES</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                while ($coord = $query_coord_exec->fetch_assoc()) {
                                    
                                
                            ?>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer"><?= $coord['nome_usuario'] ?></td>
                                <td class="p-4"><?= $coord['cpf'] ?></td>
                                <td class="p-4"><?= $coord['nome_escola'] ?></td>
                                <td class="p-4"><?= $coord['usuario'] ?></td>
                                <td class="flex p-4 gap-2">
                                    <a href="editar_coordenador.php?id=<?=$coord['id_usuario']?>" class="bg-[#edd542] hover:bg-yellow-600 text-black font-bold px-4 py-2 p-6 rounded cursor-pointer">Editar</a>
                                    <form action="acoes.php" method="post" >
                                        <button onclick="return confirm('Deseja realmente exluir?')" type="submit" name="delete_coord" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-4 py-2 p-6 rounded cursor-pointer" value="<?=$coord['id_usuario'];?>">Excluir</button>
                                    </form> 
                                </td>
                            </tr>
                            <?php
                              }
                            ?>
                        </tbody>
                    </table>
<div class="flex justify-center gap-2 my-4 relative">
    <?php
        for($p=1;$p<=$numero_pagina;$p++){
            echo "<a class='px-4 py-2 rounded-full border bg-green-800 text-white font-bold hover:bg-green-700 transition' href='?pagina={$p}'>{$p}</a>";
        } 
    ?>
    <div class="absolute bottom-0 right-4 flex gap-3">
        <a href="adicionar_coordenador.php" 
           class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 rounded-lg shadow-md transition">
           Adicionar Coordenador
        </a>
        <a href="secretaria.php" class="bg-[#cc3732] hover:bg-red-600 text-black font-bold px-4 py-2 rounded-lg shadow-md transition">
           Sair
        </a>
    </div>
</div>

            </div>
        </section>
    </main>
</body>

</html>