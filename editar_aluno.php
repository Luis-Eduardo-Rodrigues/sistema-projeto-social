<?php
    include('conn.php');
    include('protects.php');
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <?php
        $id_aluno = $_GET['id'];
        $sql = "SELECT * FROM aluno WHERE id_aluno = '$id_aluno'";
        $query = $mysqli->query($sql) or die();
        $aluno = $query->fetch_assoc();
    ?>

    <header class="mb-8">
        <img src="./src/header.png" class="w-full" />
    </header>

    <form action="acoes.php" method="POST" class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-12 flex flex-col gap-10">
        <input type="hidden" name="aluno_id" value="<?=$aluno['id_aluno'];?>">

        <h2 class="text-3xl font-bold text-center">Editar Aluno</h2>

        <div class="grid grid-cols-2 gap-10">
            <div class="flex flex-col gap-2">
                <label>Nome:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" value="<?= $aluno['nome_aluno'] ?>" name="nome_aluno" type="text">
            </div>
            <div class="flex flex-col gap-2">
                <label>CPF:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="cpf_aluno" type="text" value="<?= $aluno['cpf_aluno'] ?>">
            </div>
            <div class="flex flex-col gap-2">
                <label>Ano:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="ano_aluno" type="text" value="<?= $aluno['ano'] ?>">
            </div>
            <div class="flex flex-col gap-2">
                <label>Código do Aluno:</label>
                <input class="w-full rounded-md px-4 py-2 border border-gray-400" name="codigo_aluno" type="text" value="<?= $aluno['codigo_aluno'] ?>">
            </div>
            <div class="flex flex-col gap-2 col-span-2">
                <label>Escola:</label>
                <select name="escola" id="escola" class="w-full px-4 py-2 rounded-md border border-gray-400">
                    <option selected value="<?=$aluno['nome_escola'];?>"><?=$aluno['nome_escola'];?></option>
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

        <h3 class="text-xl font-semibold text-center">Notas e Frequência</h3>

        <?php
            $esfera = $aluno['esfera'];

            if ($esfera === 'municipal') {
                // MUNICIPAL: 4 médias + 4 frequências
                echo '<div class="grid grid-cols-4 gap-6">';
                for ($i = 1; $i <= 4; $i++) {
                    echo '
                    <div class="flex flex-col gap-2">
                        <label>Média '.$i.':</label>
                        <input class="rounded-md px-4 py-2 border border-gray-400" 
                               value="'.$aluno['media_'.$i.'_municipal'].'" 
                               name="media_'.$i.'_municipal" type="text">
                    </div>';
                }
                for ($i = 1; $i <= 4; $i++) {
                    echo '
                    <div class="flex flex-col gap-2">
                        <label>Frequência '.$i.':</label>
                        <input class="rounded-md px-4 py-2 border border-gray-400" 
                               value="'.$aluno['frequencia_'.$i.'_municipal'].'" 
                               name="frequencia_'.$i.'_municipal" type="text">
                    </div>';
                }
                echo '</div>';

            } else {
                // ESTADUAL ou FEDERAL: 3 séries (cada uma com 8 campos)
                echo '<div class="grid grid-cols-4 gap-6">';
                
                for ($serie = 1; $serie <= 3; $serie++) {
                    echo "<h4 class='col-span-4 text-lg font-semibold mt-4'>Série $serie</h4>";
                    
                    // Médias
                    for ($i = 1; $i <= 4; $i++) {
                        $col = "media_{$i}_medio{$serie}";
                        echo '
                        <div class="flex flex-col gap-2">
                            <label>Média '.$i.' ('.$serie.'º):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="'.$aluno[$col].'" 
                                   name="'.$col.'" type="text">
                        </div>';
                    }
                    
                    // Frequências
                    for ($i = 1; $i <= 4; $i++) {
                        $col = "frequencia_{$i}_medio{$serie}";
                        echo '
                        <div class="flex flex-col gap-2">
                            <label>Frequência '.$i.' ('.$serie.'º):</label>
                            <input class="rounded-md px-4 py-2 border border-gray-400" 
                                   value="'.$aluno[$col].'" 
                                   name="'.$col.'" type="text">
                        </div>';
                    }
                }
                echo '</div>';
            }
        ?>

        <div class="flex items-center justify-center gap-6 mt-6">
            <button type="submit" name="update_aluno" class="px-6 py-3 rounded-md bg-green-700 hover:bg-green-800 text-white font-bold">Salvar</button>
            <a href="aluno.php" class="px-6 py-3 rounded-md bg-red-700 hover:bg-red-800 text-white font-bold">Voltar</a>
        </div>
    </form>
</body>
</html>
