<?php
    include "protects.php";
    require "conn.php";
    require "conn.php"; 
    
   if(isset($_SESSION['msgupaluno'])){
    if($_SESSION['msgupaluno'] != ""){
        echo "<script>alert('{$_SESSION['msgupaluno']}')</script>";
        $_SESSION['msgupaluno'] = "";
    }
}

    $query_count_alunos = "SELECT COUNT(*) FROM aluno";
    $query_count_alunos_exec = $mysqli->query($query_count_alunos) or die($mysqli->error);
    $sql_count_alunos = $query_count_alunos_exec->fetch_assoc();
    $count_alunos = $sql_count_alunos['COUNT(*)'];

    $pagina = 0;
    
    if(!isset($_GET['pagina'])){
        $pagina = 1;
    }else{
        $pagina = $_GET['pagina'] ? intval($_GET['pagina']) : 1;
    }
    
    $limit = 10;
    $offset = ($pagina - 1) * $limit;

    $numero_pagina = ceil($count_alunos / $limit);

    $query_alunos = "SELECT * FROM aluno ORDER BY nome_escola ASC LIMIT {$limit} OFFSET {$offset}";
    $query_alunos_exec = $mysqli->query($query_alunos) or die($mysqli->error);

    $query_escola = "SELECT * FROM escola";
    $query_escola_exec = $mysqli->query($query_escola) or die($mysqli->error);
    $escola = $query_escola_exec->fetch_assoc(); 
    $escola = $query_escola_exec->fetch_assoc();
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

<body class="relative">
    <header class="h-40 flex items-center relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho">
    </header>

    <main class="my-12 flex items-center justify-center flex-col gap-6">
        <section class="flex justify-center mt-6 w-[70%] flex-col">
            <div class="w-[100%] border-2 border-green-800 overflow-hidden rounded-lg shadow-lg">
                <div>
                    <table class="w-[100%] border-collapse text-lg">
                        <thead>
                            <tr class="border-b border-green-600 bg-green-100">
                                <th class="p-4 text-left">NOME</th>
                                <th class="p-4 text-left">CPF</th>
                                <th class="p-4 text-left">ESCOLA</th>
                                <th class="p-4 text-left">ANO</th>
                                <th class="p-4 text-center">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($aluno = $query_alunos_exec->fetch_assoc()) { 
                            ?>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer"><?= $aluno['nome_aluno'] ?></td>
                                <td class="p-4"><?= $aluno['cpf_aluno'] ?></td>
                                <td class="p-4"><?= $aluno['nome_escola'] ?></td>
                                <td class="p-4"><?= $aluno['ano'] ?></td>
                                <td class="display flex items-center justify-center gap-2 p-4">
                                    <a href="editar_aluno.php?id=<?=$aluno['id_aluno']?>" class="bg-[#edd542] hover:bg-yellow-600 text-black font-bold px-4 py-2 p-6 rounded cursor-pointer">Editar</a>
                                    <form action="acoes.php" method="post" >
                                        <button type="submit" name="pagamento_aluno" class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 p-6 rounded cursor-pointer" value="<?=$aluno['id_aluno'];?>">Pagamento</button>
                                    </form>  
                                    <form action="acoes.php" method="post" >
                                        <button onclick="return confirm('Deseja realmente exluir?')" type="submit" name="delete_aluno" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-4 py-2 p-6 rounded cursor-pointer" value="<?=$aluno['id_aluno'];?>">Excluir</button>
                                    </form>  
                                </td>
                            </tr>
                            <?php
                              }
                            ?>
                        </tbody>
                    </table>
                    <div class="flex justify-center gap-2 my-4">
                        <?php
                            for($p=1;$p<=$numero_pagina;$p++){
                                echo "<a class='px-4 py-2 rounded-full border border-green-800 text-white text-center font-bold bg-green-800' href='?pagina={$p}'>{$p}</a>";

                            }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="bottom-0 right-16 w-full p-6 text-end">
                <a href="adicionar_aluno.php" class="position:fixed bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 rounded cursor-pointer">Adicionar Aluno</a>
                <a href="secretaria.php" class="position:fixed bg-[#edd542] hover:bg-yellow-700 text-black font-bold px-4 py-2 rounded cursor-pointer">Sair</a>
            </div>
        </section>
    </main>
</body>

</html>