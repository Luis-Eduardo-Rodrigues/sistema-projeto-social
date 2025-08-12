<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - Sistema de Controle</title>
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
    <main class="w-full flex items-center justify-center flex-col gap-12">
        <div>
            <p class="font-bold text-[#357950] mt-12 text-2xl">SISTEMA DE CONTROLE DO “BOLSA ESTUDANTE CRATÉUS” - PÉ DE MEIA MUNICIPAL</p>
        </div>
        <div class="flex items-center gap-32">
            <div class="flex items-center justify-center flex-col gap-2 cursor-pointer" onclick="mostrarModalSecretaria()">
                <div class=" border border-2 border-[#459f6a] p-2">
                    <img src="./src/user-icon.jpg" alt="Icone de Usuario" class="w-40 cursor-pointer">
                </div>
                <p class="font-bold text-[#357950] cursor-pointer">SECRETARÍA DE EDUCAÇÃO</p>
            </div>
            <div class="flex items-center justify-center flex-col gap-2 cursor-pointer" onclick="mostrarModalCoordenador()">
                <div class=" border border-2 border-[#459f6a] p-2">
                    <img src="./src/user-icon.jpg" alt="Icone de Usuario" class="w-40 cursor-pointer">
                </div>
                <p class="font-bold text-[#357950] cursor-pointer">COORDERNADOR(A)</p>
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
    <section class="flex items-center flex-col gap-6 p-4 bg-[#4bac72] absolute top-50 left-150 w-72 hidden" id="modalCoordenador">
        <div class="flex items-center justify-between w-full">
            <h2 class="text-2xl font-bold text-white">LOGIN</h2>
            <button onclick="fecharModalCoordenador()">X</button>
        </div>
        <input type="text" class="w-48 border-1 rounded-md" placeholder="Usuario">
        <input type="password" class="w-48 border-1 rounded-md" placeholder="Senha">
        <select name="" id="">
            <option value="">Escolha sua escola</option>
            <option value="Olavo Bilac">Olavo Bilac</option>
            <option value="Vilebaldo">Vilebaldo</option>
        </select>
        <button class="p-2 bg-purple-600 text-white">Entrar</button>
    </section>

    <section class="flex items-center flex-col gap-6 p-4 bg-[#4bac72] absolute top-50 left-150 w-72 hidden" id="modalSecretaria">
        <div class="flex items-center justify-between w-full">
            <h2 class="text-2xl font-bold text-white">LOGIN</h2>
            <button onclick="fecharModalSecretaria()">X</button>
        </div>
        <input type="text" class="w-48 border-1 rounded-md" placeholder="Usuario">
        <input type="password" class="w-48 border-1 rounded-md" placeholder="Senha">
        <button class="p-2 bg-purple-600 text-white">Entrar</button>
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