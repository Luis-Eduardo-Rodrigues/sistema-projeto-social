<?php
include "conn.php";

$escolas = [];
$anos = [];

$nivelSelecionado = $_POST['nivel'] ?? 'todos';

function esc($str) {
    global $mysqli;
    return $mysqli->real_escape_string($str);
}

if ($nivelSelecionado == 'fundamental' || $nivelSelecionado == 'todos') {
    $res_fund = $mysqli->query("SELECT DISTINCT nome_escola AS escola FROM aluno WHERE nome_escola IS NOT NULL AND nome_escola != '' ORDER BY nome_escola");
    while ($row = $res_fund->fetch_assoc()) {
        $escolas[esc($row['escola'])] = esc($row['escola']);
    }

    $res_anos_fund = $mysqli->query("SELECT DISTINCT ano AS ano_valor FROM aluno WHERE ano IS NOT NULL ORDER BY ano DESC");
    while ($row = $res_anos_fund->fetch_assoc()) {
        $anos[$row['ano_valor']] = $row['ano_valor'];
    }
}

if ($nivelSelecionado == 'medio' || $nivelSelecionado == 'todos') {
    $res_medio = $mysqli->query("SELECT DISTINCT nome_escola_medio AS escola FROM aluno WHERE nome_escola_medio IS NOT NULL AND nome_escola_medio != '' ORDER BY nome_escola_medio");
    while ($row = $res_medio->fetch_assoc()) {
        $escolas[esc($row['escola'])] = esc($row['escola']);
    }

    $res_anos_medio = $mysqli->query("SELECT DISTINCT ano_medio AS ano_valor FROM aluno WHERE ano_medio IS NOT NULL ORDER BY ano_medio DESC");
    while ($row = $res_anos_medio->fetch_assoc()) {
        $anos[$row['ano_valor']] = $row['ano_valor'];
    }
}

if (!empty($escolas)) ksort($escolas);
if (!empty($anos)) rsort($anos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Escola - Pé de Meia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <header class="h-40 relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho" class="w-full h-full object-cover">
    </header>
        
    <main class="container mx-auto px-6 py-10">
        <form action="gerar_pdf_escola.php" method="POST" class="space-y-8" id="form-relatorio">

            <input type="hidden" name="nivel" id="nivel-input" value="<?= htmlspecialchars($nivelSelecionado) ?>">

            <div class="grid grid-cols-2 gap-8">

                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Opções de Ensino e Série</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">Ensino</h3>
                            <div class="grid grid-cols-1 gap-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel_temp" value="fundamental" class="accent-[#FDC901]"
                                        <?= ($nivelSelecionado == 'fundamental') ? 'checked' : ''; ?>>
                                    Fundamental
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel_temp" value="medio" class="accent-[#FDC901]"
                                        <?= ($nivelSelecionado == 'medio') ? 'checked' : ''; ?>>
                                    Médio
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="nivel_temp" value="todos" class="accent-[#FDC901]"
                                        <?= ($nivelSelecionado == 'todos') ? 'checked' : ''; ?>>
                                    Todos
                                </label>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Séries</h3>
                            <div class="grid grid-cols-1 gap-2">
                                <?php foreach (["9º Ano", "1º Médio", "2º Médio", "3º Médio"] as $serie): ?>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="serie[]" value="<?= htmlspecialchars($serie) ?>"
                                            class="accent-[#FDC901]">
                                        <?= $serie ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Escolas</h2>

                    <select name="escola" id="escola-select" class="w-full border rounded-lg p-2 mb-2">
                        <option value="">Selecione uma escola</option>
                        <?php foreach ($escolas as $escola): ?>
                            <option value="<?= htmlspecialchars($escola) ?>"><?= htmlspecialchars($escola) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="selecionar-todas-escolas" name="todas_escolas" value="1" class="mr-2 accent-[#FDC901]">
                        <label for="selecionar-todas-escolas" class="text-sm">Selecionar todas as escolas</label>
                    </div>
                </section>

                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Médias (Notas)</h2>
                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Bimestres</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="bimestre_media[]" value="<?= $i ?>"
                                        class="accent-[#FDC901]">
                                    <?= $i ?>º Bimestre
                                </label>
                            <?php endfor; ?>
                            <label class="flex items-center gap-2 col-span-2">
                                <input type="checkbox" name="media_total" value="1" class="accent-[#FDC901]">
                                Média Total
                            </label>
                        </div>
                    </div>
                </section>

                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Frequência</h2>
                    <div class="mb-4">
                        <h3 class="font-semibold mb-2">Bimestres</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="bimestre_frequencia[]" value="<?= $i ?>"
                                        class="accent-[#FDC901]">
                                    <?= $i ?>º Bimestre
                                </label>
                            <?php endfor; ?>
                            <label class="flex items-center gap-2 col-span-2">
                                <input type="checkbox" name="frequencia_total" value="1" class="accent-[#FDC901]">
                                Frequência Total
                            </label>
                        </div>
                    </div>
                </section>

                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Pagamentos</h2>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="pagamentos" value="1" class="accent-[#FDC901]">
                        Quantidade de pagamentos feitos
                    </label>
                </section>

                <!-- ANOS -->
                <section class="bg-white border border-[#2CA965] rounded-xl p-6 shadow">
                    <h2 class="text-lg font-bold mb-4">Ano</h2>
                    <select name="ano" class="w-full border rounded-lg p-2" required>
                        <option value="">Selecione o ano</option>
                        <?php foreach ($anos as $ano): ?>
                            <option value="<?= $ano ?>" <?= (isset($_POST['ano']) && $_POST['ano'] == $ano) ? 'selected' : '' ?>>
                                <?= $ano ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </section>

            </div>

            <div class="flex justify-between items-center">
                <a href="index.php" class="bg-gray-300 hover:bg-gray-400 text-black font-bold px-6 py-2 rounded-lg shadow flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="bg-[#FDC901] hover:bg-yellow-500 text-black font-bold px-6 py-2 rounded-lg shadow flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> Gerar PDF
                </button>
            </div>

        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nivelRadios = document.querySelectorAll('input[name="nivel_temp"]');
            const escolaSelect = document.getElementById('escola-select');
            const selecionarTodasCheckbox = document.getElementById('selecionar-todas-escolas');
            const nivelInput = document.getElementById('nivel-input');

            nivelRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    nivelInput.value = this.value;
                });
            });

            selecionarTodasCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    escolaSelect.value = '';
                    escolaSelect.disabled = true;
                } else {
                    escolaSelect.disabled = false;
                }
            });

            document.getElementById('form-relatorio').addEventListener('submit', function (e) {
                const nivelAtual = document.querySelector('input[name="nivel_temp"]:checked');
                if (nivelAtual) {
                    nivelInput.value = nivelAtual.value;
                }

                if (selecionarTodasCheckbox.checked) {
                    escolaSelect.value = '';
                }
            });

            function atualizarLabel() {
                const nivel = document.querySelector('input[name="nivel_temp"]:checked')?.value;
                const label = selecionarTodasCheckbox.nextElementSibling;
                let texto = 'município';
                if (nivel === 'fundamental') texto = 'ensino fundamental';
                else if (nivel === 'medio') texto = 'ensino médio';
                label.textContent = `Selecionar todas as escolas do ${texto}`;
            }

            atualizarLabel();
            nivelRadios.forEach(radio => radio.addEventListener('change', atualizarLabel));
        });
    </script>
</body>
</html>