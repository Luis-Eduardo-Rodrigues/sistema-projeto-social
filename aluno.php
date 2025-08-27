<?php
    include "protects.php";
    require "conn.php";

    if(isset($_SESSION['msgupaluno']) && $_SESSION['msgupaluno'] != ""){
        echo "<script>alert('{$_SESSION['msgupaluno']}')</script>";
        $_SESSION['msgupaluno'] = "";
    }

    $anoSistema = date('Y');
    $anoAnterior = $anoSistema - 1;
    $anoAnteAnterior = $anoSistema - 2;

    // Contagem de alunos
    $query_count_alunos = "
        SELECT COUNT(*) as total 
        FROM aluno a
        JOIN escola e ON (
            (e.esfera = 'Municipal' AND a.nome_escola = e.nome_escola) 
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.nome_escola_medio = e.nome_escola)
        )
        WHERE 
            (e.esfera = 'Municipal' AND a.ano = '$anoSistema')
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.ano_medio IN ('$anoSistema','$anoAnterior','$anoAnteAnterior'))
    ";
    $res_count = $mysqli->query($query_count_alunos) or die($mysqli->error);
    $row_count = $res_count->fetch_assoc();
    $count_alunos = $row_count['total'];

    // paginação
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $limit = 10;
    $offset = ($pagina - 1) * $limit;
    $numero_pagina = ceil($count_alunos / $limit);

    // Listagem de alunos
    $query_alunos = "
        SELECT a.*, e.esfera
        FROM aluno a
        JOIN escola e ON (
            (e.esfera = 'Municipal' AND a.nome_escola = e.nome_escola) 
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.nome_escola_medio = e.nome_escola)
        )
        WHERE 
            (e.esfera = 'Municipal' AND a.ano = '$anoSistema')
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.ano_medio IN ('$anoSistema','$anoAnterior','$anoAnteAnterior'))
        ORDER BY a.nome_escola ASC
        LIMIT {$limit} OFFSET {$offset}
    ";
    $query_alunos_exec = $mysqli->query($query_alunos) or die($mysqli->error);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Secretaria - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>*{font-family:Poppins}</style>
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
                            <?php while ($aluno = $query_alunos_exec->fetch_assoc()) { ?>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer"><?= $aluno['nome_aluno'] ?></td>
                                <td class="p-4"><?= $aluno['cpf_aluno'] ?></td>
                                <td class="p-4">
                                    <?php 
                                        if ($aluno['esfera'] == "Municipal") {
                                            echo $aluno['nome_escola'];
                                        } else {
                                            echo $aluno['nome_escola_medio'];
                                        }
                                    ?>
                                </td>
                                <td class="p-4">
                                    <?php 
                                        if ($aluno['esfera'] == "Municipal") {
                                            echo $aluno['ano'];
                                        } else {
                                            echo $aluno['ano_medio'];
                                        }
                                    ?>
                                </td>
                                <td class="flex items-center justify-center gap-2 p-4">
                                    <a href="editar_aluno.php?id=<?=$aluno['id_aluno']?>" class="bg-[#edd542] hover:bg-yellow-600 text-black font-bold px-4 py-2 rounded cursor-pointer">Editar</a>
                                    <form action="acoes.php" method="post">
                                        <button type="submit" name="pagamento_aluno" class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 rounded cursor-pointer" value="<?=$aluno['id_aluno'];?>">Pagamento</button>
                                    </form>  
                                    <form action="acoes.php" method="post">
                                        <button onclick="return confirm('Deseja realmente excluir?')" type="submit" name="delete_aluno" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-4 py-2 rounded cursor-pointer" value="<?=$aluno['id_aluno'];?>">Excluir</button>
                                    </form>  
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="flex justify-center gap-2 my-4">
                        <?php for($p=1;$p<=$numero_pagina;$p++): ?>
                            <a class="px-4 py-2 rounded-full border border-green-800 text-white text-center font-bold bg-green-800" href="?pagina=<?=$p?>"><?=$p?></a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            
            <div class="bottom-0 right-16 w-full p-6 text-end">
                <a href="adicionar_aluno.php" class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 rounded cursor-pointer">Adicionar Aluno</a>
                <a href="logout.php" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-4 py-2 rounded cursor-pointer">Sair</a>
            </div>
        </section>
    </main>
</body>
</html>
