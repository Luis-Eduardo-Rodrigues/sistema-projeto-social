<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conn.php";
include "protects.php";

function list_escolas(mysqli $db): array {
    $out = [];
    $res = $db->query("SELECT nome_escola FROM escola ORDER BY nome_escola");
    if ($res) while ($r = $res->fetch_assoc()) $out[] = $r['nome_escola'];
    return $out;
}

function where_by_escolas(mysqli $db, array $escolas): string {
    if (empty($escolas)) return "";
    $safe = array_map([$db, 'real_escape_string'], $escolas);
    $lista = "'" . implode("','", $safe) . "'";

    return "WHERE (a.nome_escola IN ($lista) OR a.nome_escola_medio IN ($lista))";
}

function query_painel(mysqli $db, array $escolas = []) : array {
    $where = where_by_escolas($db, $escolas);

    $sqlKpi = "
        SELECT 
            COUNT(*) AS bolsistas,

            AVG( COALESCE(
                ( IFNULL(a.media_1_municipal,0) + IFNULL(a.media_2_municipal,0) + IFNULL(a.media_3_municipal,0) + IFNULL(a.media_4_municipal,0) )
                / NULLIF( (a.media_1_municipal IS NOT NULL) + (a.media_2_municipal IS NOT NULL) + (a.media_3_municipal IS NOT NULL) + (a.media_4_municipal IS NOT NULL), 0 ),

                ( IFNULL(a.media_1_medio1,0) + IFNULL(a.media_2_medio1,0) + IFNULL(a.media_3_medio1,0) + IFNULL(a.media_4_medio1,0) )
                / NULLIF( (a.media_1_medio1 IS NOT NULL) + (a.media_2_medio1 IS NOT NULL) + (a.media_3_medio1 IS NOT NULL) + (a.media_4_medio1 IS NOT NULL), 0 ),

                ( IFNULL(a.media_1_medio2,0) + IFNULL(a.media_2_medio2,0) + IFNULL(a.media_3_medio2,0) + IFNULL(a.media_4_medio2,0) )
                / NULLIF( (a.media_1_medio2 IS NOT NULL) + (a.media_2_medio2 IS NOT NULL) + (a.media_3_medio2 IS NOT NULL) + (a.media_4_medio2 IS NOT NULL), 0 ),

                ( IFNULL(a.media_1_medio3,0) + IFNULL(a.media_2_medio3,0) + IFNULL(a.media_3_medio3,0) + IFNULL(a.media_4_medio3,0) )
                / NULLIF( (a.media_1_medio3 IS NOT NULL) + (a.media_2_medio3 IS NOT NULL) + (a.media_3_medio3 IS NOT NULL) + (a.media_4_medio3 IS NOT NULL), 0 )
            ) ) AS media_escolar,

            AVG( COALESCE(
                ( IFNULL(a.frequencia_1_municipal,0) + IFNULL(a.frequencia_2_municipal,0) + IFNULL(a.frequencia_3_municipal,0) + IFNULL(a.frequencia_4_municipal,0) )
                / NULLIF( (a.frequencia_1_municipal IS NOT NULL) + (a.frequencia_2_municipal IS NOT NULL) + (a.frequencia_3_municipal IS NOT NULL) + (a.frequencia_4_municipal IS NOT NULL), 0 ),

                ( IFNULL(a.frequencia_1_medio1,0) + IFNULL(a.frequencia_2_medio1,0) + IFNULL(a.frequencia_3_medio1,0) + IFNULL(a.frequencia_4_medio1,0) )
                / NULLIF( (a.frequencia_1_medio1 IS NOT NULL) + (a.frequencia_2_medio1 IS NOT NULL) + (a.frequencia_3_medio1 IS NOT NULL) + (a.frequencia_4_medio1 IS NOT NULL), 0 ),

                ( IFNULL(a.frequencia_1_medio2,0) + IFNULL(a.frequencia_2_medio2,0) + IFNULL(a.frequencia_3_medio2,0) + IFNULL(a.frequencia_4_medio2,0) )
                / NULLIF( (a.frequencia_1_medio2 IS NOT NULL) + (a.frequencia_2_medio2 IS NOT NULL) + (a.frequencia_3_medio2 IS NOT NULL) + (a.frequencia_4_medio2 IS NOT NULL), 0 ),

                ( IFNULL(a.frequencia_1_medio3,0) + IFNULL(a.frequencia_2_medio3,0) + IFNULL(a.frequencia_3_medio3,0) + IFNULL(a.frequencia_4_medio3,0) )
                / NULLIF( (a.frequencia_1_medio3 IS NOT NULL) + (a.frequencia_2_medio3 IS NOT NULL) + (a.frequencia_3_medio3 IS NOT NULL) + (a.frequencia_4_medio3 IS NOT NULL), 0 )
            ) ) AS frequencia
        FROM aluno a
        $where
    ";
    $res = $db->query($sqlKpi);
    $kpis = $res ? ($res->fetch_assoc() ?: ["bolsistas"=>0,"media_escolar"=>0,"frequencia"=>0]) : ["bolsistas"=>0,"media_escolar"=>0,"frequencia"=>0];

    $sqlMed = "
        SELECT 
            AVG(COALESCE(a.media_1_municipal, a.media_1_medio1, a.media_1_medio2, a.media_1_medio3)) AS m1,
            AVG(COALESCE(a.media_2_municipal, a.media_2_medio1, a.media_2_medio2, a.media_2_medio3)) AS m2,
            AVG(COALESCE(a.media_3_municipal, a.media_3_medio1, a.media_3_medio2, a.media_3_medio3)) AS m3,
            AVG(COALESCE(a.media_4_municipal, a.media_4_medio1, a.media_4_medio2, a.media_4_medio3)) AS m4
        FROM aluno a
        $where
    ";
    $res = $db->query($sqlMed);
    $media = [['Trimestre','Média']];
    if ($res && $r = $res->fetch_assoc()) {
        $media[] = ['1', (float)$r['m1']];
        $media[] = ['2', (float)$r['m2']];
        $media[] = ['3', (float)$r['m3']];
        $media[] = ['4', (float)$r['m4']];
    }

    $sqlFreq = "
        SELECT 
            AVG(COALESCE(a.frequencia_1_municipal, a.frequencia_1_medio1, a.frequencia_1_medio2, a.frequencia_1_medio3)) AS f1,
            AVG(COALESCE(a.frequencia_2_municipal, a.frequencia_2_medio1, a.frequencia_2_medio2, a.frequencia_2_medio3)) AS f2,
            AVG(COALESCE(a.frequencia_3_municipal, a.frequencia_3_medio1, a.frequencia_3_medio2, a.frequencia_3_medio3)) AS f3,
            AVG(COALESCE(a.frequencia_4_municipal, a.frequencia_4_medio1, a.frequencia_4_medio2, a.frequencia_4_medio3)) AS f4
        FROM aluno a
        $where
    ";
    $res = $db->query($sqlFreq);
    $freq = [['Trimestre','Frequência']];
    if ($res && $r = $res->fetch_assoc()) {
        $freq[] = ['1', (float)$r['f1']];
        $freq[] = ['2', (float)$r['f2']];
        $freq[] = ['3', (float)$r['f3']];
        $freq[] = ['4', (float)$r['f4']];
    }

    $whereInner = $where; 
    $sqlBar = "
        SELECT escola, SUM(qtd) AS qtd FROM (
            SELECT a.nome_escola AS escola, COUNT(*) AS qtd
            FROM aluno a
            $whereInner
            GROUP BY a.nome_escola
            UNION ALL
            SELECT a.nome_escola_medio AS escola, COUNT(*) AS qtd
            FROM aluno a
            $whereInner
            GROUP BY a.nome_escola_medio
        ) t
        WHERE escola IS NOT NULL AND escola <> ''
        GROUP BY escola
        ORDER BY qtd DESC
        LIMIT 20
    ";
    $res = $db->query($sqlBar);
    $bar = [['Escola','Bolsistas']];
    if ($res) while ($r = $res->fetch_assoc()) $bar[] = [$r['escola'], (int)$r['qtd']];
    if (count($bar) === 1) $bar[] = ['Sem dados', 0];

    return [
        "kpis"       => $kpis,
        "media"      => $media,
        "frequencia" => $freq,
        "porEscola"  => $bar
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['ajax'])) {
    $input   = json_decode(file_get_contents("php://input"), true) ?? [];
    $escolas = is_array($input['escolas'] ?? null) ? $input['escolas'] : [];
    $payload = query_painel($mysqli, $escolas);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

$payloadInicial  = query_painel($mysqli, []);
$escolasLista    = list_escolas($mysqli);
$kpis            = $payloadInicial['kpis'];
$evolucaoMedia   = $payloadInicial['media'];
$evolucaoFreq    = $payloadInicial['frequencia'];
$barData         = $payloadInicial['porEscola'];
$frequenciaGauge = (float)($kpis['frequencia'] ?? 0);
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
    <style>*{font-family:Poppins}</style>

    <script>
        google.charts.load('current', { packages: ['corechart', 'gauge'] });
        google.charts.setOnLoadCallback(init);

        let charts = {};
        let lastData = null;

        function drawAll(media, freq, gaugeVal, barras) {
            lastData = { media, freq, gaugeVal, barras };

            const dataMedia = google.visualization.arrayToDataTable(media);
            const optArea  = {
                legend: { position: 'bottom' },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#10b981'],
                hAxis: { title: 'Bimestres' },
                vAxis: { title: 'Média', viewWindow: { min: 0 } },
                chartArea: { left: 48, top: 32, width: '85%', height: '65%' },
                pointSize: 6
            };
            charts.media = new google.visualization.AreaChart(document.getElementById('chart_media'));
            charts.media.draw(dataMedia, optArea);

            const dataFreq = google.visualization.arrayToDataTable(freq);
            const optFreq  = {
                legend: { position: 'bottom' },
                curveType: 'function',
                areaOpacity: 0.25,
                colors: ['#059669'],
                hAxis: { title: 'Bimestres' },
                vAxis: { title: '% Frequência', viewWindow: { min: 0, max: 100 } },
                chartArea: { left: 48, top: 32, width: '85%', height: '65%' },
                pointSize: 6
            };
            charts.freq = new google.visualization.AreaChart(document.getElementById('chart_freq'));
            charts.freq.draw(dataFreq, optFreq);

            const gaugeNum = parseFloat(gaugeVal || 0) || 0;
            const dataGauge = google.visualization.arrayToDataTable([['Label','Value'], ['', gaugeNum]]);
            const formatter = new google.visualization.NumberFormat({ suffix: '%' });
            formatter.format(dataGauge, 1);
            charts.gauge = new google.visualization.Gauge(document.getElementById('chart_gauge'));
            charts.gauge.draw(dataGauge, {
                width: 400, height: 300,
                max: 100, min: 0, minorTicks: 5,
                greenFrom: 80, greenTo: 100,
                redFrom: 0,  redTo: 80,
                majorTicks: ['0%', '20%', '40%', '60%', '80%', '100%']
            });

            const dataBar = google.visualization.arrayToDataTable(barras);
            charts.barras = new google.visualization.ColumnChart(document.getElementById('chart_barras'));
            charts.barras.draw(dataBar, {
                legend: { position: 'none' },
                hAxis: { slantedText: true, slantedTextAngle: 30 },
                vAxis: { title: 'Bolsistas' },
                chartArea: { left: 60, top: 30, width: '84%', height: '65%' },
            });
        }

        async function fetchUpdate() {
            const escolhidas = Array.from(document.querySelectorAll('.chk-escola:checked')).map(c => c.value);
            const resp = await fetch('?ajax=1', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ escolas: escolhidas })
            });
            const data = await resp.json();

            const bolsistas = parseInt(data?.kpis?.bolsistas ?? 0, 10) || 0;
            const mediaKpi  = parseFloat(data?.kpis?.media_escolar ?? 0) || 0;

            document.getElementById('kpi_bolsistas').textContent = bolsistas;
            document.getElementById('kpi_media').textContent     = mediaKpi.toFixed(2).replace('.', ',');

            drawAll(data.media, data.frequencia, data?.kpis?.frequencia ?? 0, data.porEscola);
        }

        function init() {
            drawAll(<?= json_encode($evolucaoMedia) ?>, <?= json_encode($evolucaoFreq) ?>, <?= json_encode($frequenciaGauge) ?>, <?= json_encode($barData) ?>);

            document.querySelectorAll('.chk-escola').forEach(chk => {
                chk.addEventListener('change', fetchUpdate);
            });

            window.addEventListener('resize', () => {
                if (!lastData) return;
                drawAll(lastData.media, lastData.freq, lastData.gaugeVal, lastData.barras);
            });
        }

        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('translate-x-full');
            document.getElementById('overlay').classList.toggle('hidden');
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

    <div id="overlay" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40" onclick="toggleMenu()"></div>

    <div id="sidebar" 
     class="fixed top-0 right-0 h-full w-64 bg-gradient-to-b from-[#4bac72] via-[#4bac72] to-[#EDD542] 
            shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col p-4 z-50">

        <button onclick="toggleMenu()" 
            class="self-end mb-6 text-white text-2xl font-bold cursor-pointer hover:text-red-500 transition">✕</button>

        <?php
            $menu = [
                ['Aluno', '<i class="fa-solid fa-graduation-cap"></i>', 'aluno.php'],
                ['Escola', '<i class="fa-solid fa-school"></i>', 'escola.php'],
                ['Coordenador', '<i class="fa-solid fa-user"></i>', 'coordenadores.php'],
                ['Montar Relatório', '<i class="fa-solid fa-file-lines"></i>', 'relatorio.php'] 
            ];
            foreach ($menu as $item) {
                echo '<a href="' . $item[2] . '" 
                         class="flex items-center gap-3 w-full bg-white rounded-lg p-3 mb-3 text-gray-700 font-medium 
                                hover:bg-green-100 hover:text-green-700 cursor-pointer shadow-sm transition">';
                echo $item[1];
                echo '<span>' . $item[0] . '</span>';
                echo '</a>';
            }
        ?>

        <a href="logout.php" 
           class="flex justify-center items-center w-full bg-red-600 text-white font-semibold rounded-lg p-3 mt-auto 
                  hover:bg-red-700 cursor-pointer shadow-md transition">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Sair
        </a>
    </div>

    <main class="m-5">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:col-span-3">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-gray-500">Quantidade de Bolsistas</div>
                    <div id="kpi_bolsistas" class="text-3xl font-bold"><?= (int)$kpis['bolsistas'] ?></div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-gray-500">Média Escolar</div>
                    <div id="kpi_media" class="text-3xl font-bold"><?= number_format((float)$kpis['media_escolar'], 2, ',', '.') ?></div>
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
            <div id="chart_barras" class="bg-white rounded-xl shadow p-2 h-96 col-span-2"></div>
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="font-bold mb-2">Escolas</h3>
                <ul class="space-y-2 max-h-80 overflow-y-auto pr-2">
                    <?php foreach ($escolasLista as $e): ?>
                        <li>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="chk-escola form-checkbox text-emerald-600" value="<?= htmlspecialchars($e) ?>">
                                <span><?= htmlspecialchars($e) ?></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="document.querySelectorAll('.chk-escola:checked').forEach(c=>c.checked=false); fetchUpdate();" 
                        class="mt-3 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 rounded-lg">
                    Limpar seleção (todas)
                </button>
            </div>
        </div>
    </main>
</body>
</html>
