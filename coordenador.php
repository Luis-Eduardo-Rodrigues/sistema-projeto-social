<?php
    include "protectc.php";
    require "conn.php"; 

    $escola_c = $_SESSION['escola'];
    $ano = date('Y');
    
    if(isset($_SESSION['msg'])){
    echo "<script>alert('{$_SESSION['msg']}')</script>";
    unset($_SESSION['msg']);
    }
   
    $query_count_alunos = "SELECT COUNT(*) FROM aluno WHERE nome_escola = '$escola_c' AND ano = '$ano'";
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

    $query_alunos = "SELECT * FROM aluno WHERE nome_escola = '$escola_c' ORDER BY nome_aluno ASC LIMIT {$limit} OFFSET {$offset}";
    $query_alunos_exec = $mysqli->query($query_alunos) or die($mysqli->error);

    $query_escola = "SELECT * FROM escola WHERE nome_escola = '$escola_c'";
    $query_escola_exec = $mysqli->query($query_escola) or die($mysqli->error);
    $escola = $query_escola_exec->fetch_assoc();

    if (isset($_POST['alterar_bimestre'])) {
        if($_SESSION['bimestre'] == 4) {
            $_SESSION['bimestre'] = 1 ;
            $bimestrenovo = $_SESSION['bimestre'];
            echo "<script> alert('$bimestrenovo') </script>";
            $id_usuario = $_SESSION['id_usuario'];
            $sql = "UPDATE usuario SET bimestre = $bimestrenovo WHERE id_usuario = $id_usuario";
            $sql_query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
            $_SESSION['bimestre'] = $bimestrenovo;
        }else{
            $bimestrenovo = $_SESSION['bimestre'] + 1;
            echo "<script> alert('$bimestrenovo') </script>";
            $id_usuario = $_SESSION['id_usuario'];
            $sql = "UPDATE usuario SET bimestre = $bimestrenovo WHERE id_usuario = $id_usuario";
            $sql_query = $mysqli->query($sql) or die("Falha na execução do código SQL: " . $mysqli->error);
            $_SESSION['bimestre'] = $bimestrenovo;

        }

        
    }

    if(isset($_POST["add_media_frequencia"])){
        $aluno = $query_alunos_exec->fetch_assoc();
        $id_aluno = $_POST["add_media_frequencia"];

        $media = $_POST[$id_aluno];
        $frequencia = $_POST[$id_aluno . "2"];

        if ($_SESSION['bimestre'] == 1) {
            $sqlaluno = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
            $query = $mysqli->query($sqlaluno) or die("". $mysqli->error);
            $aluno = $query->fetch_assoc();

            if($aluno['media_1'] == 0 AND $aluno['frequencia_1'] == 0){
                $sql = "UPDATE aluno SET media_1 = $media, frequencia_1 = $frequencia WHERE id_aluno = $id_aluno";
                mysqli_query($mysqli, $sql);
                $_SESSION['msg'] = "Aluno atualizado!";
                header("Location: coordenador.php");
                    exit;
            }else{
                $_SESSION['msg'] = "Aluno não atualizado!";
                header("Location: coordenador.php");
                    exit;
            }

            
                           
        }elseif ($_SESSION["bimestre"] == 2) {
            $sqlaluno = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
            $query = $mysqli->query($sqlaluno) or die("". $mysqli->error);
            $aluno = $query->fetch_assoc();

            if($aluno['media_2'] == 0 AND $aluno['frequencia_2'] == 0){
                $sql = "UPDATE aluno SET media_2 = $media, frequencia_2 = $frequencia WHERE id_aluno = $id_aluno";
                mysqli_query($mysqli, $sql);
                $_SESSION['msg'] = "Aluno atualizado!";
                header("Location: coordenador.php");
                    exit;
            }else{
                $_SESSION['msg'] = "Aluno não atualizado!";
                header("Location: coordenador.php");
                    exit;
            }

            
               
        }elseif ($_SESSION["bimestre"] == 3) {
            $sqlaluno = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
            $query = $mysqli->query($sqlaluno) or die("". $mysqli->error);
            $aluno = $query->fetch_assoc();

            if($aluno['media_3'] == 0 AND $aluno['frequencia_3'] == 0){
                $sql = "UPDATE aluno SET media_3 = $media, frequencia_3 = $frequencia WHERE id_aluno = $id_aluno";
                mysqli_query($mysqli, $sql);
                $_SESSION['msg'] = "Aluno atualizado!";
                header("Location: coordenador.php");
                exit;
            }else{
                $_SESSION['msg'] = "Aluno não atualizado!";
                header("Location: coordenador.php");
                    exit;
            }
          
        }elseif ($_SESSION["bimestre"] == 4) {
            $sqlaluno = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
            $query = $mysqli->query($sqlaluno) or die("". $mysqli->error);
            $aluno = $query->fetch_assoc();

            if($aluno['media_4'] == 0 AND $aluno['frequencia_4'] == 0){
                $sql = "UPDATE aluno SET media_4 = $media, frequencia_4 = $frequencia WHERE id_aluno = $id_aluno";
                mysqli_query($mysqli, $sql);
                $_SESSION['msg'] = "Aluno atualizado!";
                header("Location: coordenador.php");
                exit;
            }else{
                $_SESSION['msg'] = "Aluno não atualizado!";
                header("Location: coordenador.php");
                exit;
            }
            
            
        }


    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela do Coordenador - Sistema de Controle</title>
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
        
        <section class="bg-[linear-gradient(100deg,rgba(75,172,114,1)_80%,rgba(243,225,114,1)_100%)] w-[90%] p-6 rounded-md flex flex-col gap-6">
            <div class="text-xl text-white font-bold">Escola: <span class="font-normal"><?= $escola['nome_escola'] ?></span></div>
            <div class="text-xl text-white font-bold">Endereço: <span class="font-normal"><?= $escola['endereco_escola']?></span></div>
            <div class="text-xl text-white font-bold">Total de Alunos: <span class="font-normal"><?= $escola['qtd_alunos'] ?></span></div>
        </section>

        <section class="flex justify-center mt-6 w-[70%]">
            <div class="w-[100%] border-2 border-green-800 overflow-hidden rounded-lg shadow-lg">
                <div>
                    <table class="w-[100%] border-collapse text-lg">
                        <thead>
                            <tr class="border-b border-green-600 bg-green-100">
                                <th class="p-4 text-left">NOME</th>
                                <th class="p-4 text-left">CPF</th>
                                <th class="p-4 text-left">MÉDIA</th>
                                <th class="p-4 text-left">FREQUENCIA</th>
                                <th class="p-4 text-left">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $b = $_SESSION['bimestre']; 
                                $campo_media = "media_" . $b;
                                $campo_freq  = "frequencia_" . $b;

                                while ($aluno = $query_alunos_exec->fetch_assoc()) {
                            ?>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer"><?= $aluno['nome_aluno'] ?></td>
                                <td class="p-4"><?= $aluno['cpf_aluno'] ?></td>
                                <form action="" method="post">
                                    <td class="p-4">
                                        <input type="text" 
                                               name="<?=$aluno['id_aluno']?>" 
                                               class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" 
                                               value="<?= $aluno[$campo_media] ?>" 
                                               placeholder="Média" />
                                    </td>
                                    <td class="p-4">
                                        <input type="text" 
                                               name="<?=$aluno['id_aluno'] . "2"?>" 
                                               class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" 
                                               value="<?= $aluno[$campo_freq] ?>" 
                                               placeholder="0%" />
                                    </td>
                                    <td class="p-4">
                                        <button class="bg-green-700 text-white font-bold px-4 py-2 rounded cursor-pointer" name="add_media_frequencia" value="<?=$aluno['id_aluno'];?>" >Salvar</button>
                                    </td>
                                </form>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="flex justify-center gap-2 my-4 relative">
                        <?php
                            for($p=1;$p<=$numero_pagina;$p++){
                                echo "<a class='px-4 py-2 rounded-full border border-green-800 text-white text-center font-bold bg-green-800' href='?pagina={$p}'>{$p}</a>";
                            }
                        ?>
                        <form action="" method="post" class="">
                            <a href="logout.php" class="px-4 py-2 bg-red-800 text-white font-bold absolute right-0 mr-6 rounded-md">Sair</a>
                            <button type="submit" class="px-4 py-2 rounded text-white font-bold bg-green-700 absolute right-24 cursor-pointer" name="alterar_bimestre">Encerrar bimestre</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
