<?php 
include "conn.php";
$kpis = [
    'bolsistas' => 100,
    'media_escolar' => 5.25,
    'frequencia' => 71.5
];

$evolucaoMedia = [
    ['Trimestre', 'Média'],
    ['1', 4],
    ['2', 8],
    ['3', 7.2],
    ['4', 2.3],
];

$evolucaoFrequencia = [
    ['Trimestre', 'Frequência'],
    ['1', 65],
    ['2', 64.5],
    ['3', 85],
    ['4', 72],
];

$escolas = [
    "Almeida",
    "Azevedo Araújo - ME",
    "Barros",
    "Caldeira e Filhos",
    "Caldeira Mendes Ltda.",
    "Campos",
    "Almeida",
    "Azevedo Araújo - ME",
    "Barros",
    "Caldeira e Filhos",
    "Caldeira Mendes Ltda.",
    "Campos",
    "Almeida",
    "Azevedo Araújo - ME",
    "Barros",
    "Caldeira e Filhos",
    "Caldeira Mendes Ltda.",
    "Campos",
    "Almeida",
    "Azevedo Araújo - ME",
    "Barros",
    "Caldeira e Filhos",
    "Caldeira Mendes Ltda.",
    "Campos"
];

$mapPoints = [
    ['Brazil', 100],
    ['Portugal', 20],
    ['Spain', 10]
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pé de Meia</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: Poppins }
    </style>
    <script>
        google.charts.load('current', { packages: ['corechart', 'gauge', 'geochart'] });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            const dataMedia = google.visualization.arrayToDataTable(<?= json_encode($evolucaoMedia) ?>);
            const optMedia = {
                legend: { position: 'bottom' },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#10b981'],
                hAxis: { title: 'Trimestres' },
                vAxis: { title: 'Média', viewWindow: { min: 0 } },
                chartArea: { left: 48, top: 32, width: '85%', height: '65%' }
            };
            new google.visualization.AreaChart(document.getElementById('chart_media')).draw(dataMedia, optMedia);

            const dataFreq = google.visualization.arrayToDataTable(<?= json_encode($evolucaoFrequencia) ?>);
            const optFreq = {
                legend: { position: 'bottom' },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#059669'],
                hAxis: { title: 'Trimestres' },
                vAxis: { title: '% Frequência', viewWindow: { min: 0, max: 100 } },
                chartArea: { left: 48, top: 32, width: '85%', height: '65%' }
            };
            new google.visualization.AreaChart(document.getElementById('chart_freq')).draw(dataFreq, optFreq);

            const dataGauge = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['', <?= (float)$kpis['frequencia'] ?>]
            ]);
            const formatter = new google.visualization.NumberFormat({ suffix: '%' });
            formatter.format(dataGauge, 1);
            const optGauge = {
                width: 400, height: 300, max: 100, min: 0, minorTicks: 5,
                greenFrom: 80, greenTo: 100, redFrom: 0, redTo: 80,
                majorTicks: ['0%', '20%', '40%', '60%', '80%', '100%']
            };
            new google.visualization.Gauge(document.getElementById('chart_gauge')).draw(dataGauge, optGauge);

            const dataMap = google.visualization.arrayToDataTable([
                ['Local', 'Bolsistas'],
                <?php foreach ($mapPoints as $p) echo "['{$p[0]}', {$p[1]}],"; ?>
            ]);
            const optMap = { displayMode: 'markers' };
            new google.visualization.GeoChart(document.getElementById('chart_geo')).draw(dataMap, optMap);
        }

        window.addEventListener('resize', () => {
            if (google?.charts) drawCharts();
        });

        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-slate-50">
    <header class="h-40 relative">
        <img src="./src/header.png" alt="Imagem Cabeçalho" class="w-full h-full object-cover">
        <button onclick="toggleMenu()">
            <i class="fa-solid fa-bars absolute right-4 top-1/6 transform -translate-y-1/2 text-[#2CA965] text-4xl cursor-pointer"></i>
        </button>
    </header>

    <!-- Overlay com blur -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40" onclick="toggleMenu()"></div>

    <!-- Menu lateral -->
    <div id="sidebar" class="fixed top-0 right-0 h-full w-64 bg-gradient-to-b from-[rgba(75,172,114,1)] to-[rgba(243,225,114,1)] shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col p-4 z-50"
>
        <button onclick="toggleMenu()" class="self-end mb-4 text-white text-xl font-bold cursor-pointer">✕</button>
        <?php
        $menu = [
            ['Aluno', '<i class="fa-solid fa-graduation-cap"></i>'],
            ['Escola', '<i class="fa-solid fa-school"></i>'],
            ['Coordenador', '<i class="fa-solid fa-user"></i>']
        ];
        foreach ($menu as $item) {
            echo '<button class="flex justify-between items-center w-full bg-white rounded-lg p-3 mb-2 hover:bg-gray-100 cursor-pointer">';
            echo '<span>' . $item[0] . '</span>';
            echo '<span>' . $item[1] . '</span>';
            echo '</button>';
        }
        ?>
    </div>

    <main class="m-5">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:col-span-3">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-gray-500">Quantidade de Bolsistas</div>
                    <div class="text-3xl font-bold"><?= $kpis['bolsistas'] ?></div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-gray-500">Média Escolar</div>
                    <div class="text-3xl font-bold"><?= number_format($kpis['media_escolar'], 2, ',', '.') ?></div>
                </div>
                <div id="chart_media" class="bg-white rounded-xl shadow p-2 h-80"></div>
                <div id="chart_freq" class="bg-white rounded-xl shadow p-2 h-80"></div>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <div class="text-gray-500">Frequência Escolar</div>
                <div class="p-4 flex items-center justify-center h-[360px]">
                    <div id="chart_gauge" class="flex items-center justify-center"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div id="chart_geo" class="bg-white rounded-xl shadow p-2 h-96 col-span-2"></div>
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="font-bold mb-2">Escolas</h3>
                <ul class="space-y-2 max-h-80 overflow-y-auto pr-2">
                    <?php foreach ($escolas as $e): ?>
                        <li>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="form-checkbox text-emerald-600">
                                <span><?= htmlspecialchars($e) ?></span>
                            </label>
                        </li>
                   / <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>
