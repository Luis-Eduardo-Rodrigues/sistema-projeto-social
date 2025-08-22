<?php  
include "conn.php";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pé de Meia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: Poppins; }
    </style>
</head>

<body class="bg-gray-100">
    <header class="h-40 relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho" class="w-full h-full object-cover">
    </header>

    <main class="container mx-auto px-6 py-10">
        <form action="gerar_pdf.php" method="POST" class="space-y-8">

            <!-- Grid principal com 2 colunas -->
            <div class="grid grid-cols-2 gap-8">

                <!-- OPÇÕES DE ENSINO E SÉRIE -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Opções de Ensino e Série</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">Ensino</h3>
                            <div class="grid grid-cols-1 gap-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel" value="fundamental" class="accent-[#FDC901]">
                                    Fundamental
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel" value="medio" class="accent-[#FDC901]">
                                    Médio
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel" value="todos" class="accent-[#FDC901]">
                                    Todos
                                </label>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Séries</h3>
                            <div class="grid grid-cols-1 gap-2">
                                <?php foreach (["9º Ano","1º Médio","2º Médio","3º Médio"] as $serie): ?>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="serie[]" value="<?= $serie ?>" class="accent-[#FDC901]">
                                        <?= $serie ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ESCOLAS (SELECT) -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Escolas</h2>
                    <select name="escola" class="w-full border rounded-lg p-2">
                        <option value="">Selecione a escola</option>
                        <?php for ($i=1; $i<=10; $i++): ?>
                            <option value="Escola<?= $i ?>">Escola <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </section>

                <!-- MÉDIAS (Notas) -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Médias (Notas)</h2>
                    
                    <!-- Bimestres -->
                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Bimestres</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php for ($i=1; $i<=4; $i++): ?>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="bimestre_media[]" value="<?= $i ?>" class="accent-[#FDC901]">
                                    <?= $i ?>º Bimestre
                                </label>
                            <?php endfor; ?>
                            <label class="flex items-center gap-2 col-span-2">
                                <input type="checkbox" name="media_total" value="1" class="accent-[#FDC901]">
                                Total
                            </label>
                        </div>
                    </div>

                    <!-- Classificação -->
                    <div>
                        <h3 class="font-semibold mb-2">Meta</h3>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_media" value="acima" class="accent-[#FDC901]">
                                Acima da Meta
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_media" value="abaixo" class="accent-[#FDC901]">
                                Abaixo da Meta
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_media" value="todos" class="accent-[#FDC901]">
                                Todos
                            </label>
                        </div>
                    </div>
                </section>

                <!-- FREQUÊNCIA -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Frequência</h2>
                    
                    <!-- Bimestres -->
                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Bimestres</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php for ($i=1; $i<=4; $i++): ?>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="bimestre_frequencia[]" value="<?= $i ?>" class="accent-[#FDC901]">
                                    <?= $i ?>º Bimestre
                                </label>
                            <?php endfor; ?>
                            <label class="flex items-center gap-2 col-span-2">
                                <input type="checkbox" name="frequencia_total" value="1" class="accent-[#FDC901]">
                                Total
                            </label>
                        </div>
                    </div>

                    <!-- Classificação -->
                    <div>
                        <h3 class="font-semibold mb-2">Meta</h3>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_frequencia" value="acima" class="accent-[#FDC901]">
                                Acima da Meta
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_frequencia" value="abaixo" class="accent-[#FDC901]">
                                Abaixo da Meta
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="meta_frequencia" value="todos" class="accent-[#FDC901]">
                                Todos
                            </label>
                        </div>
                    </div>
                </section>

                <!-- PAGAMENTOS -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Pagamentos</h2>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="pagamentos" value="1" class="accent-[#FDC901]">
                        Incluir pagamentos no relatório
                    </label>
                </section>

                <!-- ANOS (SELECT) -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Ano</h2>
                    <select name="ano" class="w-full border rounded-lg p-2">
                        <option value="">Selecione o ano</option>
                        <?php foreach ([2025, 2024, 2023] as $ano): ?>
                            <option value="<?= $ano ?>"><?= $ano ?></option>
                        <?php endforeach; ?>
                    </select>
                </section>

            </div>

            <!-- BOTÕES -->
            <div class="flex justify-between items-center">
                <a href="index.php" class="bg-gray-300 hover:bg-gray-400 text-black font-bold px-6 py-2 rounded-lg shadow">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="bg-[#FDC901] hover:bg-yellow-500 text-black font-bold px-6 py-2 rounded-lg shadow">
                    <i class="fa-solid fa-file-pdf"></i> Gerar PDF
                </button>
            </div>
        </form>
    </main>
</body>
</html>
