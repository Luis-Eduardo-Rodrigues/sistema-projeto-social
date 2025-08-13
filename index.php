<?php
include "conn.php";

if (isset($_POST['usuario_c']) || isset($_POST['senha_c'])) {
        
        if(strlen($_POST['usuario_c']) == 0){
            echo "<script> alert('Preencha seu usuário!') </script>";
        }else if(strlen($_POST['senha_c']) == 0){
            echo "<script> alert('Preencha sua senha!') </script>";
        }else{
            
            $usuario_c = $mysqli->real_escape_string($_POST['usuario_c']);
            $senha_c = $mysqli->real_escape_string($_POST['senha_c']);
            $escola_c = $mysqli->real_escape_string($_POST['escola']);

            $sql_code = "SELECT * FROM usuario WHERE usuario = '$usuario_c' LIMIT 1";
            $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

            $usuario = $sql_query->fetch_assoc();

            if(password_verify($senha_c ,$usuario['senha'])){
                

                if(!isset($_SESSION)){
                    session_start();
                }

                
                if($escola_c == $usuario['nome_escola']){
                    $_SESSION['escola'] = $usuario['nome_escola'];
                    $_SESSION['nome_coordenador'] = $usuario['nome_usuario'];
                    header("Location: coordenador.php");
                }else{
                    echo "<script> alert('Falha ao logar! Dados incorretos') </script>";
                }
                

                

            }else{
                echo "<script> alert('Falha ao logar! Dados incorretos') </script>";
            }
        }

    }

if(isset($_POST['usuario_s']) AND isset($_POST['senha_s'])){
    if(strlen($_POST['usuario_s']) == 0){
        echo "<script> alert('Preencha o campo de usuário.') </script>";
    }else if(strlen($_POST['senha_s']) == 0){
        echo "<script>alert('Preencha o campo de senha.')</script>";
    } else{

        $nome_secretario = $mysqli->real_escape_string($_POST['usuario_s']);
        $senha_secretario = $mysqli->real_escape_string($_POST['senha_s']);

        $consulta_sql = "SELECT * FROM usuario WHERE usuario = '$nome_secretario' LIMIT 1";
        $execucao_sql = $mysqli->query($consulta_sql) or die("Falha no código SQL.");

        $usuario = $execucao_sql->fetch_assoc();
        
        if(password_verify($senha_secretario, $usuario['senha'])){

            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION['nome_secretario'] = $usuario['usuario'];
            $_SESSION['senha_secretario'] = $usuario['senha'];

            header("Location: secretaria.php");
        }else{
            echo "<script>alert('Usuario ou senha incorretos.')</script>";
        }



    }

}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - Sistema de Controle</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" href="src/projeto.png" type="image/png">
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
    <main class="w-full flex items-center justify-center flex-col gap-12">
        <div>
            <p class="font-bold text-black mt-12 text-2xl">SISTEMA DE CONTROLE DO “BOLSA ESTUDANTE CRATÉUS” - PÉ DE MEIA MUNICIPAL</p>
        </div>
        <div class="flex items-center gap-32">
    <div class="flex items-center justify-center flex-col gap-2 cursor-pointer group" onclick="mostrarModalSecretaria()" id="modalSecretariaBtn">
        <div class="w-[300px] border-2 border-[#459f6a] rounded-xl p-2 flex items-center justify-center flex-col gap-2 transition-colors duration-300 
                    hover:bg-[#357950]">
            <i class="fa-solid fa-user text-9xl text-[#357950] group-hover:text-white transition-colors duration-300"></i>
            <p class="font-bold text-[#357950] group-hover:text-white transition-colors duration-300">SECRETARIA DE EDUCAÇÃO</p>
        </div>
    </div>
    <div class="flex items-center justify-center flex-col gap-2 cursor-pointer group" onclick="mostrarModalCoordenador()" id="modalCoordenadorBtn">
        <div class="w-[300px] border-2 border-[#459f6a] rounded-xl p-2 flex items-center justify-center flex-col gap-2 transition-colors duration-300 
                    hover:bg-[#357950]">
            <i class="fa-solid fa-user text-9xl text-[#357950] group-hover:text-white transition-colors duration-300"></i>
            <p class="font-bold text-[#357950] group-hover:text-white transition-colors duration-300">COORDENADOR(A)</p>
        </div>
    </div>
</div>

    </main>
    <footer class="w-full flex items-center py-2 justify-around mt-24 border-t-2 border-[#357950]">
        <div>
            <img src="./src/logo-caixa.png" class="w-40" alt="Logo da Caixa">
        </div>
        <div>
            <img src="./src/logo-gov.png" class="w-40" alt="Logo do Governo Federal">
        </div>
        <div>
            <img src="./src/logo-estado.png" class="w-40" alt="Logo do Estado">
        </div>
    </footer>





    <!-- modais -->

    <!--coordenador-->
    <section class="flex items-center flex-col gap-6 p-4 absolute top-50 left-150 w-72 hidden" id="modalCoordenador">
        <form action="" method="post">
            <div class="w-80 bg-[#5cd65c] rounded-md ">
                <h2 class="text-lg font-bold text-center mb-4 text-white">LOGIN</h2>

                <div class="bg-[#5cd65c] shadow-md  p-4 space-y-4" >
                    <label for="usuario" class="block text-sm text-white mb-1">Usuário</label>
                    <input id="usuario" name="usuario_c" type="text" class="shadow-lg w-full bg-gray-50 border border-white rounded-md ">
                </div>

                <div class="bg-[#5cd65c] shadow-md p-4">
                    <label for="senha" class="block text-sm text-white mb-1">Senha</label>
                    <input id="senha" name="senha_c" type="text" class="shadow-lg w-full bg-gray-50 border border-white rounded-md ">
                </div>

                <div class=" bg-[#5cd65c] shadow-md p-4">
                    <select name="escola" id="escola" class="block text-sm text-white mb-1">
                    <option value="" class="shadow-lg w-full bg-gray-50 border border-white rounded-md">Escolha sua escola</option>
                     <?php
                        $sql = "SELECT * FROM escola";
                        $escolas = mysqli_query($mysqli, $sql);
                        foreach($escolas as $escola){
                    ?>
                    
                    
                    <option value="<?=$escola['nome_escola']?>" class="shadow-lg text-black w-full bg-gray-50 border border-white rounded-md"><?=$escola['nome_escola']?></option>
                    <?php
                        }
                    ?>
                </select>
                </div>

                <div class="text-right ">
                    <button type="submit" class="w-full text-sm bg[#1e7b1e] hover:bg-green-600 px-5 rounded-md p-2 text-white font-bold">Entrar</button>
                </div>
            </div>
        
        </form>        
        
    
    </section>

    <!--secretaria-->
     <section class="flex items-center flex-col gap-6 p-4 absolute top-50 left-150 w-72 hidden" id="modalSecretaria">
        <form action="" method="post">
            <div class="w-80 bg-[#5cd65c] rounded-md ">
            <h2 class="text-lg font-bold text-center mb-4 text-white">LOGIN</h2>

            <div class="bg-[#5cd65c] shadow-md  p-4 space-y-4" >
                <label for="usuario" class="block text-sm text-white mb-1">Usuário</label>
                <input id="usuario" name="usuario_s" type="text" class="shadow-lg w-full bg-gray-50 border border-white rounded-md ">
            </div>

            <div class="bg-[#5cd65c] shadow-md p-4">
                <label for="senha" class="block text-sm text-white mb-1">Senha</label>
                <input id="senha" name="senha_s" type="text" class="shadow-lg w-full bg-gray-50 border border-white rounded-md ">
            </div>

            <div class="text-right ">
                <button type="submit" class="w-full text-sm bg[#1e7b1e] hover:bg-green-600 px-5 rounded-md p-2 text-white font-bold">Entrar</button>
            </div>

        </form>
        
    </div>
    
    </section>


    <script>
        function mostrarModalCoordenador(){
            const modalCoordenador = document.getElementById("modalCoordenador")
            modalCoordenador.classList.remove("hidden")
        }

        function fecharModalCoordenador(){
            const modalCoordenador = document.getElementById("modalCoordenador")

            modalCoordenador.classList.add("hidden")
        }


        function mostrarModalSecretaria(){
            const modalSecretaria = document.getElementById("modalSecretaria")
            modalSecretaria.classList.remove("hidden")
        }

        function fecharModalSecretaria(){
            const modalSecretaria = document.getElementById("modalSecretaria")
            modalSecretaria.classList.add("hidden")
        }
    </script>
</body>


</html>
