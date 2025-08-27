<?php
   include "protectc.php";
   require "conn.php"; 

   $escola_c = $_SESSION['escola'];
   $anoSistema = date('Y');
   $anoAnterior = $anoSistema - 1;
   $anoAnteAnterior = $anoSistema - 2;
   
   if(isset($_SESSION['msg'])){
       echo "<script>alert('{$_SESSION['msg']}')</script>";
       unset($_SESSION['msg']);
   }

   // pega dados da escola e esfera
   $query_escola = "SELECT * FROM escola WHERE nome_escola = '$escola_c'";
   $query_escola_exec = $mysqli->query($query_escola) or die($mysqli->error);
   $escola = $query_escola_exec->fetch_assoc();
   $esfera = $escola['esfera'];

   // contar alunos
   if ($esfera == "Municipal") {
       $query_count_alunos = "SELECT COUNT(*) FROM aluno 
                              WHERE nome_escola = '$escola_c' 
                                AND ano = '$anoSistema'";
   } else {
       $query_count_alunos = "SELECT COUNT(*) FROM aluno 
                              WHERE nome_escola = '$escola_c' 
                                AND ano IN ('$anoSistema','$anoAnterior','$anoAnteAnterior')";
   }
   $query_count_alunos_exec = $mysqli->query($query_count_alunos) or die($mysqli->error);
   $sql_count_alunos = $query_count_alunos_exec->fetch_assoc();
   $count_alunos = $sql_count_alunos['COUNT(*)'];

   // paginação
   $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
   $limit = 10;
   $offset = ($pagina - 1) * $limit;
   $numero_pagina = ceil($count_alunos / $limit);

   // buscar alunos
   if ($esfera == "Municipal") {
       $query_alunos = "SELECT * FROM aluno 
                        WHERE nome_escola = '$escola_c' 
                          AND ano = '$anoSistema'
                        ORDER BY nome_aluno ASC 
                        LIMIT {$limit} OFFSET {$offset}";
   } else {
       $query_alunos = "SELECT * FROM aluno 
                        WHERE nome_escola = '$escola_c' 
                          AND ano IN ('$anoSistema','$anoAnterior','$anoAnteAnterior')
                        ORDER BY nome_aluno ASC 
                        LIMIT {$limit} OFFSET {$offset}";
   }
   $query_alunos_exec = $mysqli->query($query_alunos) or die($mysqli->error);

   // Alterar bimestre
   if (isset($_POST['alterar_bimestre'])) {
       if($_SESSION['bimestre'] == 4) {
           $_SESSION['bimestre'] = 1;
       } else {
           $_SESSION['bimestre']++;
       }
       $bimestrenovo = $_SESSION['bimestre'];
       echo "<script>alert('$bimestrenovo')</script>";
       $id_usuario = $_SESSION['id_usuario'];
       $sql = "UPDATE usuario SET bimestre = $bimestrenovo WHERE id_usuario = $id_usuario";
       $mysqli->query($sql) or die("Falha SQL: " . $mysqli->error);
   }

   // Salvar médias/frequências
   if(isset($_POST["add_media_frequencia"])){
       $id_aluno = $_POST["add_media_frequencia"];
       $media = $_POST[$id_aluno];
       $frequencia = $_POST[$id_aluno . "2"];
       $b = $_SESSION['bimestre'];

       $sqlaluno = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
       $query = $mysqli->query($sqlaluno) or die("". $mysqli->error);
       $aluno = $query->fetch_assoc();

       if ($esfera == "Municipal") {
           $colMedia = "media_{$b}_municipal";
           $colFreq  = "frequencia_{$b}_municipal";
       } else {
           if ($aluno['ano'] == $anoSistema) {
               $colMedia = "media_{$b}_medio1";
               $colFreq  = "frequencia_{$b}_medio1";
           } elseif ($aluno['ano'] == $anoAnterior) {
               $colMedia = "media_{$b}_medio2";
               $colFreq  = "frequencia_{$b}_medio2";
           } elseif ($aluno['ano'] == $anoAnteAnterior) {
               $colMedia = "media_{$b}_medio3";
               $colFreq  = "frequencia_{$b}_medio3";
           } else {
               $_SESSION['msg'] = "Ano do aluno fora do intervalo permitido!";
               header("Location: coordenador.php");
               exit;
           }
       }

       if ($aluno[$colMedia] == 0 && $aluno[$colFreq] == 0) {
           $sql = "UPDATE aluno SET $colMedia = $media, $colFreq = $frequencia WHERE id_aluno = $id_aluno";
           mysqli_query($mysqli, $sql);
           $_SESSION['msg'] = "Aluno atualizado!";
       } else {
           $_SESSION['msg'] = "Aluno não atualizado!";
       }

       header("Location: coordenador.php");
       exit;
   }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela do Coordenador - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>*{font-family:Poppins}</style>
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

        while ($aluno = $query_alunos_exec->fetch_assoc()) {
            // decide quais colunas mostrar
            if ($esfera == "Municipal") {
                $mediaAtual = $aluno["media_{$b}_municipal"];
                $freqAtual  = $aluno["frequencia_{$b}_municipal"];
            } else {
                if ($aluno['ano'] == $anoSistema) {
                    $mediaAtual = $aluno["media_{$b}_medio1"];
                    $freqAtual  = $aluno["frequencia_{$b}_medio1"];
                } elseif ($aluno['ano'] == $anoAnterior) {
                    $mediaAtual = $aluno["media_{$b}_medio2"];
                    $freqAtual  = $aluno["frequencia_{$b}_medio2"];
                } elseif ($aluno['ano'] == $anoAnteAnterior) {
                    $mediaAtual = $aluno["media_{$b}_medio3"];
                    $freqAtual  = $aluno["frequencia_{$b}_medio3"];
                } else {
                    $mediaAtual = 0;
                    $freqAtual  = 0;
                }
            }

            // verificação acumulada até o bimestre atual
            $atingiu = true;
            for ($i=1; $i<=$b; $i++) {
                if ($esfera == "Municipal") {
                    $mediaCheck = $aluno["media_{$i}_municipal"];
                    $freqCheck  = $aluno["frequencia_{$i}_municipal"];
                } else {
                    if ($aluno['ano'] == $anoSistema) {
                        $mediaCheck = $aluno["media_{$i}_medio1"];
                        $freqCheck  = $aluno["frequencia_{$i}_medio1"];
                    } elseif ($aluno['ano'] == $anoAnterior) {
                        $mediaCheck = $aluno["media_{$i}_medio2"];
                        $freqCheck  = $aluno["frequencia_{$i}_medio2"];
                    } elseif ($aluno['ano'] == $anoAnteAnterior) {
                        $mediaCheck = $aluno["media_{$i}_medio3"];
                        $freqCheck  = $aluno["frequencia_{$i}_medio3"];
                    } else {
                        $mediaCheck = 0;
                        $freqCheck = 0;
                    }
                }

                if ($mediaCheck < 6 || $freqCheck < 75) {
                    $atingiu = false;
                    break;
                }
            }

            $linhaClasse = $atingiu ? "border-b hover:bg-green-50" : "border-b bg-red-200 hover:bg-red-300";
    ?>
    <tr class="<?= $linhaClasse ?>">
        <td class="p-4 cursor-pointer"><?= $aluno['nome_aluno'] ?></td>
        <td class="p-4"><?= $aluno['cpf_aluno'] ?></td>
        <form action="" method="post">
            <td class="p-4">
                <input type="text" 
                       name="<?=$aluno['id_aluno']?>" 
                       class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" 
                       value="<?= $mediaAtual ?>" 
                       placeholder="Média"/>
            </td>
            <td class="p-4">
                <input type="text" 
                       name="<?=$aluno['id_aluno'] . "2"?>" 
                       class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" 
                       value="<?= $freqAtual ?>" 
                       placeholder="0%" />
            </td>
            <td class="p-4">
                <button class="bg-green-700 text-white font-bold px-4 py-2 rounded cursor-pointer" 
                        name="add_media_frequencia" 
                        value="<?=$aluno['id_aluno'];?>">Salvar</button>
            </td>
        </form>
    </tr>
    <?php } ?>
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
