<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conn.php";
include "protects.php";


$sql_escolas = "SELECT nome_escola FROM escola";
$sql_escolas_exec = $mysqli->query($sql_escolas) or die("Falha na consulta SQL". $mysqli->error);

while($escola = $sql_escolas_exec->fetch_assoc()){
    $nome_escola = $escola["nome_escola"];
    
    $query_coord = "SELECT bimestre FROM usuario WHERE nome_escola = '$nome_escola'";
    $query_coord_exec = $mysqli->query( $query_coord ) or die("Falha na consulta SQL". $mysqli->error);
    $bimestre = $query_coord_exec->fetch_assoc();

    if($bimestre['bimestre'] == 1) {
        $sql = "SELECT AVG(media_1_municipal) AS media_1 FROM aluno WHERE nome_escola = '$nome_escola' ";
        $sql_exec = $mysqli->query($sql) or die("". $mysqli->error);
        $media = $sql_exec->fetch_assoc();
        $media1 = $media["media_1"];

        $sql_up = "UPDATE escola SET media_total1 = '$media1' WHERE nome_escola = '$nome_escola'";
        $sql_up_exec = $mysqli->query( $sql_up ) or die("". $mysqli->error);

    }elseif($bimestre['bimestre'] == 2) {
        $sql = "SELECT AVG(media_2) AS media_2 FROM aluno WHERE nome_escola = '$nome_escola' ";
        $sql_exec = $mysqli->query( $sql ) or die("". $mysqli->error);
        $media = $sql_exec->fetch_assoc();
        $media2 = $media["media_2"];

        $sql_up = "UPDATE escola SET media_total2 = '$media2' WHERE nome_escola = '$nome_escola'";
        $sql_up_exec = $mysqli->query( $sql_up ) or die("". $mysqli->error);

    }elseif($bimestre['bimestre'] == 3) {
        $sql = "SELECT AVG(media_3) AS media_3 FROM aluno WHERE nome_escola = '$nome_escola' ";
        $sql_exec = $mysqli->query( $sql ) or die("". $mysqli->error);
        $media = $sql_exec->fetch_assoc();
        $media3 = $media["media_3"];

        $sql_up = "UPDATE escola SET media_total3 = '$media3'";
        $sql_up_exec = $mysqli->query( $sql_up ) or die("". $mysqli->error);

    }elseif($bimestre['bimestre'] == 4) {
        $sql = "SELECT AVG(media_4) AS media_4 FROM aluno WHERE nome_escola = '$nome_escola' ";
        $sql_exec = $mysqli->query( $sql ) or die("". $mysqli->error);
        $media = $sql_exec->fetch_assoc();
        $media4 = $media["media_4"];

        $sql_up = "UPDATE escola SET media_total4 = '$media4' WHERE nome_escola = '$nome_escola'";
        $sql_up_exec = $mysqli->query( $sql_up ) or die("". $mysqli->error);
    }
}









// KPIs usando media_total e frequencia_total
$sql = "SELECT 
            COUNT(*) AS bolsistas,
            AVG(media_total1) AS media_escolar,
            AVG(frequencia_total) AS frequencia
        FROM escola";
$res = $mysqli->query($sql);
if (!$res) die("Erro KPIs: " . $mysqli->error);
$kpis = $res->fetch_assoc();

// Evolução das médias por trimestre
$evolucaoMedia = [['Trimestre', 'Média']];
$sql = "SELECT 
            AVG(media_1_municipal) AS m1,
            AVG(media_2_municipal) AS m2,
            AVG(media_3_municipal) AS m3,
            AVG(media_4_municipal) AS m4
        FROM aluno";
$res = $mysqli->query($sql);
if (!$res) die("Erro Evolução Média: " . $mysqli->error);
if ($row = $res->fetch_assoc()) {
    $evolucaoMedia[] = ['1', (float)$row['m1']];
    $evolucaoMedia[] = ['2', (float)$row['m2']];
    $evolucaoMedia[] = ['3', (float)$row['m3']];
    $evolucaoMedia[] = ['4', (float)$row['m4']];
}

// Evolução da frequência por trimestre
$evolucaoFrequencia = [['Trimestre', 'Frequência']];
$sql = "SELECT 
            AVG(frequencia_1_municipal) AS f1,
            AVG(frequencia_2_municipal) AS f2,
            AVG(frequencia_3_municipal) AS f3,
            AVG(frequencia_4_municipal) AS f4
        FROM aluno";
$res = $mysqli->query($sql);
if (!$res) die("Erro Evolução Frequência: " . $mysqli->error);
if ($row = $res->fetch_assoc()) {
    $evolucaoFrequencia[] = ['1', (float)$row['f1']];
    $evolucaoFrequencia[] = ['2', (float)$row['f2']];
    $evolucaoFrequencia[] = ['3', (float)$row['f3']];
    $evolucaoFrequencia[] = ['4', (float)$row['f4']];
}

// Lista de escolas
$escolas = [];
$res = $mysqli->query("SELECT nome_escola FROM escola ORDER BY nome_escola");
if (!$res) die("Erro Escolas: " . $mysqli->error);
while ($row = $res->fetch_assoc()) {
    $escolas[] = $row['nome_escola'];
}

// Valor da frequência para o gauge
$frequenciaGauge = isset($kpis['frequencia']) ? (float)$kpis['frequencia'] : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pé de Meia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: Poppins;
        }
    </style>
    <script>
        google.charts.load('current', {
            packages: ['corechart', 'gauge', 'geochart']
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            const dataMedia = google.visualization.arrayToDataTable(<?= json_encode($evolucaoMedia) ?>);
            const optMedia = {
                legend: {
                    position: 'bottom'
                },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#10b981'],
                hAxis: {
                    title: 'Bimestres'
                },
                vAxis: {
                    title: 'Média',
                    viewWindow: {
                        min: 0
                    }
                },
                chartArea: {
                    left: 48,
                    top: 32,
                    width: '85%',
                    height: '65%'
                },
                pointSize: 6 
            };
            new google.visualization.AreaChart(document.getElementById('chart_media')).draw(dataMedia, optMedia);



            // Frequência por trimestre
            const dataFreq = google.visualization.arrayToDataTable(<?= json_encode($evolucaoFrequencia) ?>);
            const optFreq = {
                legend: {
                    position: 'bottom'
                },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#059669'],
                hAxis: {
                    title: 'Bimestres'
                },
                vAxis: {
                    title: '% Frequência',
                    viewWindow: {
                        min: 0,
                        max: 100
                    }
                },
                chartArea: {
                    left: 48,
                    top: 32,
                    width: '85%',
                    height: '65%'
                },
                pointSize: 6
            };
            new google.visualization.AreaChart(document.getElementById('chart_freq')).draw(dataFreq, optFreq);

            // Gauge
            const dataGauge = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['', <?= $frequenciaGauge ?>]
            ]);
            const formatter = new google.visualization.NumberFormat({
                suffix: '%'
            });
            formatter.format(dataGauge, 1);
            const optGauge = {
                width: 400,
                height: 300,
                max: 100,
                min: 0,
                minorTicks: 5,
                greenFrom: 80,
                greenTo: 100,
                redFrom: 0,
                redTo: 80,
                majorTicks: ['0%', '20%', '40%', '60%', '80%', '100%']
            };
            new google.visualization.Gauge(document.getElementById('chart_gauge')).draw(dataGauge, optGauge);
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
        <button onclick="toggleMenu()" class="absolute top-11 right-4 transform -translate-y-1/2 text-[#2CA965] text-4xl">
            <i class="fa-solid fa-bars cursor-pointer"></i>
        </button>
    </header>


    <!-- Overlay -->
    <div id="overlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40" onclick="toggleMenu()"></div>

    <!-- Menu lateral -->
    <div id="sidebar" class="fixed top-0 right-0 h-full w-64 bg-gradient-to-b from-[#4bac72] via-[#4bac72] to-[#EDD542] shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col p-4 z-50">
    <button onclick="toggleMenu()" class="self-end mb-4 text-white text-xl font-bold cursor-pointer">✕</button>
    <?php
    $menu = [
        ['Aluno', '<i class="fa-solid fa-graduation-cap"></i>', 'aluno.php'],
        ['Escola', '<i class="fa-solid fa-school"></i>', 'escola.php'],
        ['Coordenador', '<i class="fa-solid fa-user"></i>', 'acoescoordenador.php'],
    ];
    foreach ($menu as $item) {
        echo '<a href="' . $item[2] . '" class="flex justify-between items-center w-full bg-white rounded-lg p-3 mb-2 hover:bg-gray-100 cursor-pointer">';
        echo '<span>' . $item[0] . '</span>';
        echo '<span>' . $item[1] . '</span>';
        echo '</a>';
    }
    ?>
    
    <!-- Botão de Logout -->
    <a href="logout.php" class="flex justify-center items-center w-full bg-red-600 text-white font-semibold rounded-lg p-3 mt-auto hover:bg-red-700 cursor-pointer shadow-md">
        <i class="fa-solid fa-right-from-bracket mr-2"></i> Sair
    </a>
</div>

    </div>

    <main class="m-5">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:col-span-3">
                <!-- Cards -->
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

        <!-- Lista de escolas -->
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
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>
</body>

</html>