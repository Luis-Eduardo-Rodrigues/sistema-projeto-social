<?php
    include "protectc.php";
    require "conn.php";
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
            <div class="text-xl text-white font-bold">Escola: <span class="font-normal">Escola de Cidadania Olavo Bilac</span></div>
            <div class="text-xl text-white font-bold">Endereço: <span class="font-normal">Av. Sargento Hermínio</span></div>
            <div class="text-xl text-white font-bold">Total de Alunos: <span class="font-normal">230</span></div>
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
                                <th class="p-4 text-left">FREQUÊNCIA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer">*********</td>
                                <td class="p-4">000.000.000-00</td>
                                <td class="p-4">
                                    <input type="text" class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" placeholder="0.0" />
                                </td>
                                <td class="p-4">
                                    <input type="text" class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" placeholder="0%" />
                                </td>
                            </tr>
                            <tr class="border-b hover:bg-green-50">
                                <td class="p-4 cursor-pointer">*********</td>
                                <td class="p-4">000.000.000-00</td>
                                <td class="p-4">
                                    <input type="text" name="" class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" placeholder="0.0" />
                                </td>
                                <td class="p-4">
                                    <input type="text" class="border border-green-500 rounded-full px-3 py-2 w-24 text-center focus:outline-none" placeholder="0%" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex justify-center gap-2 my-4">
                        <button class="w-10 h-10 rounded-full border border-green-800 bg-green-800 text-white font-bold">1</button>
                        <button class="w-10 h-10 rounded-full border border-green-800 text-green-800 font-bold">2</button>
                        <button class="w-10 h-10 rounded-full border border-green-800 text-green-800 font-bold">3</button>
                        <button class="w-10 h-10 rounded-full border border-green-800 text-green-800 font-bold">4</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>