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

    $filtro_nome = isset($_GET['search']) ? trim($_GET['search']) : '';
    $filtro_nome_sql = $mysqli->real_escape_string($filtro_nome);

    $query_count_alunos = "
        SELECT COUNT(*) as total 
        FROM aluno a
        JOIN escola e ON (
            (e.esfera = 'Municipal' AND a.nome_escola = e.nome_escola) 
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.nome_escola_medio = e.nome_escola)
        )
        WHERE 
            (
                (e.esfera = 'Municipal' AND a.ano = '$anoSistema')
                OR 
                (e.esfera IN ('Estadual','Federal') AND a.ano_medio IN ('$anoSistema','$anoAnterior','$anoAnteAnterior'))
            )
            " . ($filtro_nome !== '' ? " AND a.nome_aluno LIKE '%$filtro_nome_sql%'" : "") . "
    ";
    $res_count = $mysqli->query($query_count_alunos) or die($mysqli->error);
    $row_count = $res_count->fetch_assoc();
    $count_alunos = $row_count['total'];

    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $limit = 10;
    $offset = ($pagina - 1) * $limit;
    $numero_pagina = ceil($count_alunos / $limit);

    $pagina_inicial = floor(($pagina - 1) / 10) * 10 + 1;
    $pagina_final = min($pagina_inicial + 9, $numero_pagina);

    $query_alunos = "
        SELECT a.*, e.esfera
        FROM aluno a
        JOIN escola e ON (
            (e.esfera = 'Municipal' AND a.nome_escola = e.nome_escola) 
            OR 
            (e.esfera IN ('Estadual','Federal') AND a.nome_escola_medio = e.nome_escola)
        )
        WHERE 
            (
                (e.esfera = 'Municipal' AND a.ano = '$anoSistema')
                OR 
                (e.esfera IN ('Estadual','Federal') AND a.ano_medio IN ('$anoSistema','$anoAnterior','$anoAnteAnterior'))
            )
            " . ($filtro_nome !== '' ? " AND a.nome_aluno LIKE '%$filtro_nome_sql%'" : "") . "
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

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins, sans-serif;
        }
        .header-image {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        @media (max-width: 768px) {
            .header-image {
                height: 120px;
            }
        }
        .table-container {
            overflow-x: auto;
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }
        @media (max-width: 640px) {
            .action-buttons {
                flex-direction: column;
            }
            .action-buttons a, 
            .action-buttons button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body class="relative min-h-screen flex flex-col">
    <header class="h-32 md:h-40 flex items-center relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho" class="header-image w-full">
    </header>

    <main class="my-6 md:my-12 flex-1 flex items-center justify-center flex-col gap-4 md:gap-6 px-4">
        <section class="flex justify-center mt-4 md:mt-6 w-full max-w-6xl flex-col">

            <form method="GET" class="mb-4 flex flex-col sm:flex-row gap-2 sm:items-center justify-between">
                <div class="w-full sm:w-1/2">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Buscar por nome do aluno..." 
                        value="<?= htmlspecialchars($filtro_nome) ?>"
                        class="w-full border border-green-800 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                </div>
                <button 
                    type="submit" 
                    class="bg-green-800 hover:bg-green-700 text-white font-bold px-4 py-2 rounded text-sm"
                >
                    Buscar
                </button>
            </form>

            <div class="w-full border-2 border-green-800 overflow-hidden rounded-lg shadow-lg">
                <div class="table-container">
                    <table class="w-full border-collapse text-sm md:text-base lg:text-lg">
                        <thead>
                            <tr class="border-b border-green-600 bg-green-100">
                                <th class="p-2 md:p-3 lg:p-4 text-left">NOME</th>
                                <th class="p-2 md:p-3 lg:p-4 text-left hidden sm:table-cell">CPF</th>
                                <th class="p-2 md:p-3 lg:p-4 text-left">ESCOLA</th>
                                <th class="p-2 md:p-3 lg:p-4 text-left hidden md:table-cell">ANO</th>
                                <th class="p-2 md:p-3 lg:p-4 text-center">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($aluno = $query_alunos_exec->fetch_assoc()) { ?>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-2 md:p-3 lg:p-4 cursor-pointer font-medium"><?= $aluno['nome_aluno'] ?></td>
                                <td class="p-2 md:p-3 lg:p-4 hidden sm:table-cell"><?= $aluno['cpf_aluno'] ?></td>
                                <td class="p-2 md:p-3 lg:p-4">
                                    <?php 
                                        if ($aluno['esfera'] == "Municipal") {
                                            echo $aluno['nome_escola'];
                                        } else {
                                            echo $aluno['nome_escola_medio'];
                                        }
                                    ?>
                                </td>
                                <td class="p-2 md:p-3 lg:p-4 hidden md:table-cell">
                                    <?php 
                                        if ($aluno['esfera'] == "Municipal") {
                                            echo $aluno['ano'];
                                        } else {
                                            echo $aluno['ano_medio'];
                                        }
                                    ?>
                                </td>
                                <td class="p-2 md:p-3 lg:p-4">
                                    <div class="action-buttons">
                                        <a href="editar_aluno.php?id=<?=$aluno['id_aluno']?>" class="bg-[#edd542] hover:bg-yellow-600 text-black font-bold px-2 md:px-3 lg:px-4 py-1 md:py-2 rounded cursor-pointer text-xs md:text-sm">Editar</a>
                                        <form action="acoes.php" method="post">
                                            <button type="submit" name="pagamento_aluno" class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-2 md:px-3 lg:px-4 py-1 md:py-2 rounded cursor-pointer text-xs md:text-sm" value="<?=$aluno['id_aluno'];?>">Pagamento</button>
                                        </form>  
                                        <form action="acoes.php" method="post">
                                            <button onclick="return confirm('Deseja realmente excluir?')" type="submit" name="delete_aluno" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-2 md:px-3 lg:px-4 py-1 md:py-2 rounded cursor-pointer text-xs md:text-sm" value="<?=$aluno['id_aluno'];?>">Excluir</button>
                                        </form>  
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center gap-2 my-4 flex-wrap px-2">
                    <?php for($p=$pagina_inicial; $p<=$pagina_final; $p++): ?>
                        <a class="px-3 py-1 md:px-4 md:py-2 rounded-full border border-green-800 text-white text-center font-bold bg-green-800 text-xs md:text-sm" href="?pagina=<?=$p?>&search=<?=urlencode($filtro_nome)?>"><?=$p?></a>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="bottom-0 right-0 md:right-16 w-full p-4 md:p-6 text-center md:text-end mt-4">
                <a href="adicionar_aluno.php" class="bg-[#4bac72] hover:bg-green-700 text-black font-bold px-4 py-2 rounded cursor-pointer inline-block mb-2 md:mb-0 md:mr-2 text-sm md:text-base">Adicionar Aluno</a>
                <a href="secretaria.php" class="bg-[#cc3732] hover:bg-red-700 text-black font-bold px-4 py-2 rounded cursor-pointer inline-block text-sm md:text-base">Sair</a>
            </div>
        </section>
    </main>
</body>
</html>
