<?php
include "conn.php";

// Recebe dados do formulário
$nivel = $_POST['nivel'] ?? 'todos';
$anoSelecionado = $_POST['ano'] ?? '';
$escola = $_POST['escola'] ?? '';
$todasEscolas = isset($_POST['todas_escolas']);
$bimestresMedia = $_POST['bimestre_media'] ?? [];
$bimestresFrequencia = $_POST['bimestre_frequencia'] ?? [];
$mediaTotal = isset($_POST['media_total']);
$frequenciaTotal = isset($_POST['frequencia_total']);
$pagamentos = isset($_POST['pagamentos']);

// Monta a query base
$where = [];

// Filtro por ano
if ($nivel == 'fundamental') {
    $where[] = "ano = '{$mysqli->real_escape_string($anoSelecionado)}'";
} elseif ($nivel == 'medio') {
    $where[] = "ano_medio = '{$mysqli->real_escape_string($anoSelecionado)}'";
} else {
    $where[] = "(ano = '{$mysqli->real_escape_string($anoSelecionado)}' 
                 OR ano_medio = '{$mysqli->real_escape_string($anoSelecionado)}')";
}

// Filtro de escola
if (!$todasEscolas && !empty($escola)) {
    if ($nivel == 'fundamental') {
        $where[] = "nome_escola = '{$mysqli->real_escape_string($escola)}'";
    } elseif ($nivel == 'medio') {
        $where[] = "nome_escola_medio = '{$mysqli->real_escape_string($escola)}'";
    } else {
        $where[] = "(nome_escola = '{$mysqli->real_escape_string($escola)}' 
                     OR nome_escola_medio = '{$mysqli->real_escape_string($escola)}')";
    }
}

// Monta SQL final
$sql = "SELECT * FROM aluno";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$result = $mysqli->query($sql) or die("Erro na consulta: " . $mysqli->error);

// ========================
// INÍCIO GERAÇÃO DO PDF
// ========================
require_once("fpdf/fpdf.php");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(0, 10, "Relatorio de Alunos - Programa Pé de Meia", 0, 1, "C");
$pdf->Ln(5);

// Cabeçalho da tabela
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(60, 8, "Nome do Aluno", 1);
$pdf->Cell(40, 8, "Escola", 1);
$pdf->Cell(20, 8, "Ano", 1);
if (!empty($bimestresMedia)) {
    foreach ($bimestresMedia as $b) {
        $pdf->Cell(20, 8, "M{$b}", 1);
    }
}
if ($mediaTotal) {
    $pdf->Cell(20, 8, "Media", 1);
}
if (!empty($bimestresFrequencia)) {
    foreach ($bimestresFrequencia as $b) {
        $pdf->Cell(20, 8, "F{$b}", 1);
    }
}
if ($frequenciaTotal) {
    $pdf->Cell(20, 8, "Freq", 1);
}
if ($pagamentos) {
    $pdf->Cell(25, 8, "Pagamentos", 1);
}
$pdf->Ln();

// Conteúdo da tabela
$pdf->SetFont("Arial", "", 10);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(60, 8, utf8_decode($row['nome_aluno']), 1);
    $escolaNome = $nivel == 'medio' ? $row['nome_escola_medio'] : $row['nome_escola'];
    $pdf->Cell(40, 8, utf8_decode($escolaNome), 1);
    $ano = $row['ano'] ?: $row['ano_medio'];
    $pdf->Cell(20, 8, $ano, 1);

    // Médias
    if (!empty($bimestresMedia)) {
        foreach ($bimestresMedia as $b) {
            if ($nivel == 'fundamental') {
                $coluna = "media_{$b}_municipal";
            } elseif ($nivel == 'medio') {
                $coluna = "media_{$b}_medio" . ($row['ano_medio'] - 0); // ajusta pelo ano do médio
            } else {
                // nível todos → tenta ambos
                $coluna = !empty($row['ano_medio'])
                    ? "media_{$b}_medio" . ($row['ano_medio'] - 0)
                    : "media_{$b}_municipal";
            }
            $pdf->Cell(20, 8, $row[$coluna] ?? "-", 1);
        }
    }
    if ($mediaTotal) {
        $pdf->Cell(20, 8, "-", 1); // aqui você pode calcular a média geral se quiser
    }

    // Frequência
    if (!empty($bimestresFrequencia)) {
        foreach ($bimestresFrequencia as $b) {
            if ($nivel == 'fundamental') {
                $coluna = "frequencia_{$b}_municipal";
            } elseif ($nivel == 'medio') {
                $coluna = "frequencia_{$b}_medio" . ($row['ano_medio'] - 0);
            } else {
                $coluna = !empty($row['ano_medio'])
                    ? "frequencia_{$b}_medio" . ($row['ano_medio'] - 0)
                    : "frequencia_{$b}_municipal";
            }
            $pdf->Cell(20, 8, $row[$coluna] ?? "-", 1);
        }
    }
    if ($frequenciaTotal) {
        $pdf->Cell(20, 8, "-", 1); // aqui você pode calcular a frequência total se quiser
    }

    // Pagamentos
    if ($pagamentos) {
        $pdf->Cell(25, 8, $row['pagamento'] ?? "0", 1);
    }

    $pdf->Ln();
}

$pdf->Output();
?>
