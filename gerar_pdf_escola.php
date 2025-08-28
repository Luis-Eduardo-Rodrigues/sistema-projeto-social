<?php
require "conn.php";
require "fpdf/fpdf.php";

// --- Evitar problema com BOM/echo antes do PDF ---
if (!ob_get_level()) ob_start();

// ----------------- RECEBE FILTROS -----------------
$nivel = $_POST['nivel'] ?? 'todos'; 
$escola = $_POST['escola'] ?? '';
$ano = $_POST['ano'] ?? '';

$bimestres_media = $_POST['bimestre_media'] ?? [];
$media_total = isset($_POST['media_total']);
$bimestres_freq = $_POST['bimestre_frequencia'] ?? [];
$frequencia_total = isset($_POST['frequencia_total']);
$pagamentos = isset($_POST['pagamentos']);

// ----------------- MONTA QUERY -----------------
$sqls = [];

if ($nivel === 'fundamental' || $nivel === 'todos') {
    $sql_fund = "SELECT *, nome_escola AS escola, ano AS ano_ref,
        'fundamental' AS nivel_tipo
        FROM aluno
        WHERE nome_escola IS NOT NULL AND nome_escola != ''";

    if ($escola !== '' && $escola !== 'todas') {
        $sql_fund .= " AND nome_escola = '" . $mysqli->real_escape_string($escola) . "'";
    }
    if ($ano != '') {
        $sql_fund .= " AND ano = '" . $mysqli->real_escape_string($ano) . "'";
    }

    $sqls[] = $sql_fund;
}

if ($nivel === 'medio' || $nivel === 'todos') {
    $sql_medio = "SELECT *, nome_escola_medio AS escola, ano_medio AS ano_ref,
        'medio' AS nivel_tipo
        FROM aluno
        WHERE nome_escola_medio IS NOT NULL AND nome_escola_medio != ''";

    if ($escola !== '' && $escola !== 'todas') {
        $sql_medio .= " AND nome_escola_medio = '" . $mysqli->real_escape_string($escola) . "'";
    }
    if ($ano != '') {
        $sql_medio .= " AND ano_medio = '" . $mysqli->real_escape_string($ano) . "'";
    }

    $sqls[] = $sql_medio;
}

if (empty($sqls)) die("Nenhum dado encontrado.");

$sql_final = implode(" UNION ALL ", $sqls);
$result = $mysqli->query($sql_final);
if (!$result) die("Erro na consulta: " . $mysqli->error);
if ($result->num_rows == 0) die("Nenhum aluno encontrado.");

// ----------------- AGRUPAMENTO -----------------
$dados_escolas = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['escola'] . '|' . $row['ano_ref'];

    if (!isset($dados_escolas[$key])) {
        $dados_escolas[$key] = [
            'alunos' => 0,
            'medias' => [],
            'frequencias' => [],
            'media_total' => 0,
            'frequencia_total' => 0,
            'pagamento_total' => 0,
            'nome_escola' => $row['escola'],
            'ano' => $row['ano_ref'],
            'nivel_tipo' => $row['nivel_tipo']
        ];
    }

    $dados_escolas[$key]['alunos']++;

    if ($row['nivel_tipo'] === 'medio') {
        // Consolidar todos os anos do médio (1,2,3)
        $medias = [];
        $frequencias = [];
        for ($s = 1; $s <= 3; $s++) {
            for ($b = 1; $b <= 4; $b++) {
                $campo_m = "media_{$b}_medio{$s}";
                $campo_f = "frequencia_{$b}_medio{$s}";
                if (isset($row[$campo_m])) $medias[] = floatval($row[$campo_m]);
                if (isset($row[$campo_f])) $frequencias[] = floatval($row[$campo_f]);
            }
        }
    } else {
        $medias = [
            floatval($row["media_1_municipal"] ?? 0),
            floatval($row["media_2_municipal"] ?? 0),
            floatval($row["media_3_municipal"] ?? 0),
            floatval($row["media_4_municipal"] ?? 0)
        ];
        $frequencias = [
            floatval($row["frequencia_1_municipal"] ?? 0),
            floatval($row["frequencia_2_municipal"] ?? 0),
            floatval($row["frequencia_3_municipal"] ?? 0),
            floatval($row["frequencia_4_municipal"] ?? 0)
        ];
    }

    $media_aluno = (count($medias) > 0) ? array_sum($medias) / count($medias) : 0;
    $freq_aluno = (count($frequencias) > 0) ? array_sum($frequencias) / count($frequencias) : 0;

    $dados_escolas[$key]['media_total'] += $media_aluno;
    $dados_escolas[$key]['frequencia_total'] += $freq_aluno;

    foreach ($bimestres_media as $b) {
        if ($row['nivel_tipo'] === 'medio') {
            $soma = 0;
            for ($s = 1; $s <= 3; $s++) {
                $campo = "media_{$b}_medio{$s}";
                if (isset($row[$campo])) $soma += floatval($row[$campo]);
            }
            $dados_escolas[$key]['medias'][$b] = ($dados_escolas[$key]['medias'][$b] ?? 0) + $soma;
        } else {
            $campo = "media_{$b}_municipal";
            $dados_escolas[$key]['medias'][$b] = ($dados_escolas[$key]['medias'][$b] ?? 0) + floatval($row[$campo] ?? 0);
        }
    }

    foreach ($bimestres_freq as $b) {
        if ($row['nivel_tipo'] === 'medio') {
            $soma = 0;
            for ($s = 1; $s <= 3; $s++) {
                $campo = "frequencia_{$b}_medio{$s}";
                if (isset($row[$campo])) $soma += floatval($row[$campo]);
            }
            $dados_escolas[$key]['frequencias'][$b] = ($dados_escolas[$key]['frequencias'][$b] ?? 0) + $soma;
        } else {
            $campo = "frequencia_{$b}_municipal";
            $dados_escolas[$key]['frequencias'][$b] = ($dados_escolas[$key]['frequencias'][$b] ?? 0) + floatval($row[$campo] ?? 0);
        }
    }

    $dados_escolas[$key]['pagamento_total'] += floatval($row['pagamento'] ?? 0);
}

// ---------------- CRIAÇÃO DO PDF ----------------
class PDF extends FPDF {
    var $img_esquerda = 'src/projeto.png';
    var $img_direita = 'src/governo-crateus.png';

    function Header() {
        $this->SetY(10);
        $largura_img = 40;
        if (file_exists($this->img_esquerda)) {
            $dims = getimagesize($this->img_esquerda);
            $altura_esq = $dims[1] * ($largura_img / $dims[0]);
            $this->Image($this->img_esquerda, 15, $this->GetY(), $largura_img, $altura_esq);
        }
        if (file_exists($this->img_direita)) {
            $dims = getimagesize($this->img_direita);
            $altura_dir = $dims[1] * ($largura_img / $dims[0]);
            $this->Image($this->img_direita, 155, $this->GetY(), $largura_img, $altura_dir);
        }
        $this->SetY($this->GetY() + 30);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetMargins(10,10,10);
$pdf->SetAutoPageBreak(true,15);

$pdf->SetFont('Arial','B',14);

if ($escola !== '' && $escola !== 'todas') {
    $titulo = "Relatorio de Escola - {$escola} - Ano: {$ano}";
} else {
    switch ($nivel) {
        case 'fundamental': $titulo = "Todas as Escolas do Fundamental - Ano: {$ano}"; break;
        case 'medio': $titulo = "Todas as Escolas do Médio - Ano: {$ano}"; break;
        default: $titulo = "Todas as Escolas do Município - Ano: {$ano}"; break;
    }
}

$pdf->Cell(0,10,utf8_decode($titulo),0,1,'C');
$pdf->Ln(5);

// Rótulos
$rotulos = [
    'media_1' => 'Media 1B',
    'media_2' => 'Media 2B',
    'media_3' => 'Media 3B',
    'media_4' => 'Media 4B',
    'media_total' => 'Media Total',
    'frequencia_1' => 'Freq. 1B',
    'frequencia_2' => 'Freq. 2B',
    'frequencia_3' => 'Freq. 3B',
    'frequencia_4' => 'Freq. 4B',
    'frequencia_total' => 'Freq. Total',
    'pagamento_total' => 'Pagamento'
];

foreach ($dados_escolas as $dados) {
    // Subtítulo: apenas escola + ano
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,utf8_decode("{$dados['nome_escola']} - Ano {$dados['ano']}"),0,1,'L');
    $pdf->Ln(5);

    // Definição dos campos
    $campos_pdf = [];
    foreach ($bimestres_media as $b) $campos_pdf[] = "media_{$b}";
    foreach ($bimestres_freq as $b) $campos_pdf[] = "frequencia_{$b}";
    if ($frequencia_total) $campos_pdf[] = 'frequencia_total';
    if ($media_total) $campos_pdf[] = 'media_total';
    if ($pagamentos) $campos_pdf[] = 'pagamento_total';

    // Calcular larguras
    $larguras = [];
    foreach ($campos_pdf as $c) {
        $rotulo = $rotulos[$c] ?? $c;
        $larguras[$c] = $pdf->GetStringWidth(utf8_decode($rotulo)) + 8;
    }
    $totalLarguras = array_sum($larguras);
    $fator = ($totalLarguras > 190) ? 190 / $totalLarguras : 1;
    foreach ($larguras as $k => $v) $larguras[$k] = $v * $fator;

    // Cabeçalho
    $largura_total = array_sum($larguras);
    $margem_esquerda = (210 - $largura_total) / 2;

    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(200,200,200);
    $pdf->SetX($margem_esquerda);
    foreach ($campos_pdf as $c) {
        $rotulo = $rotulos[$c] ?? $c;
        $pdf->Cell($larguras[$c],6,utf8_decode($rotulo),1,0,'C',true);
    }
    $pdf->Ln();

    // Valores
    $pdf->SetFont('Arial','',10);
    $pdf->SetX($margem_esquerda);
    foreach ($campos_pdf as $c) {
        if ($c == 'media_total') {
            $valor = round($dados['media_total'] / $dados['alunos'], 1);
            $pdf->Cell($larguras[$c],6,$valor,1,0,'C');
        } elseif ($c == 'frequencia_total') {
            $valor = round($dados['frequencia_total'] / $dados['alunos'], 1);
            $pdf->Cell($larguras[$c],6,$valor."%",1,0,'C');
        } elseif ($c == 'pagamento_total') {
            $valor = round($dados['pagamento_total'],2);
            $pdf->Cell($larguras[$c],6,$valor,1,0,'C');
        } elseif (strpos($c,'media_')===0) {
            $b = str_replace('media_','',$c);
            $valor = $dados['medias'][$b] ?? 0;
            $pdf->Cell($larguras[$c],6,round($valor / $dados['alunos'],1),1,0,'C');
        } elseif (strpos($c,'frequencia_')===0) {
            $b = str_replace('frequencia_','',$c);
            $valor = $dados['frequencias'][$b] ?? 0;
            $pdf->Cell($larguras[$c],6,round($valor / $dados['alunos'],1)."%",1,0,'C');
        }
    }
    $pdf->Ln(12);
}

ob_clean(); // garante saída limpa antes do PDF
$pdf->Output("I","Relatorio_escola.pdf");
exit;
