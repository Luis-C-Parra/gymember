<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/librerias/tcpdf/tcpdf.php';

// ------------------------------------------------------------
// CLASE PERSONALIZADA PDF
// ------------------------------------------------------------
class PDFGymember extends TCPDF {
    public function Header() {
        $logo = $_SERVER['DOCUMENT_ROOT'] . '/gymember/assets/img/imagotipo.png';
        if (file_exists($logo)) {
            $pageWidth = $this->getPageWidth();
            $logoWidth = 30;
            $x = ($pageWidth - $logoWidth) / 2;
            $this->Image($logo, $x, 8, $logoWidth);
        }
        $this->SetFont('helvetica', 'B', 18);
        $this->Ln(25);
        $this->Cell(0, 10, 'Historial de Pagos del Miembro', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $fecha = date('d/m/Y H:i');
        $this->Cell(0, 10, "Generado el $fecha - Página " . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// ------------------------------------------------------------
// DATOS
// ------------------------------------------------------------
$usuario_id = isset($_GET['usuario_id']) ? (int)$_GET['usuario_id'] : 0;
if ($usuario_id <= 0) {
    die("ID de usuario inválido");
}

// Datos del miembro y usuario
$sql_user = "
    SELECT u.nombre, u.apellido, u.email, u.telefono, m.estado, p.nombre AS plan_nombre
    FROM miembros m
    JOIN usuarios u ON m.usuario_id = u.id
    LEFT JOIN planes p ON m.plan_id = p.id
    WHERE u.id = ?
";
$stmt = $conexion->prepare($sql_user);
if (!$stmt) {
    die("Error SQL (usuario): " . $conexion->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$usuario) {
    die("Miembro no encontrado.");
}

// Pagos del miembro
$sql_pagos = "
    SELECT p.id, p.metodo, p.monto, p.fecha, pl.nombre AS plan, pl.periodo
    FROM pagos p
    LEFT JOIN planes pl ON p.plan_id = pl.id
    WHERE p.usuario_id = ?
    ORDER BY p.fecha DESC
";
$stmt = $conexion->prepare($sql_pagos);
if (!$stmt) {
    die("Error SQL (pagos): " . $conexion->error);
}
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$pagos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ------------------------------------------------------------
// CREAR PDF
// ------------------------------------------------------------
$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Historial de Pagos - ' . $usuario['nombre']);
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

// ------------------------------------------------------------
// ENCABEZADO DE INFORMACIÓN
// ------------------------------------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Nombre: ' . $usuario['nombre'] . ' ' . $usuario['apellido'], 0, 1);
$pdf->Cell(0, 8, 'Email: ' . $usuario['email'], 0, 1);
$pdf->Cell(0, 8, 'Teléfono: ' . ($usuario['telefono'] ?: '—'), 0, 1);
$pdf->Cell(0, 8, 'Estado: ' . ucfirst($usuario['estado']), 0, 1);
$pdf->Ln(5);

// ------------------------------------------------------------
// TABLA DE PAGOS
// ------------------------------------------------------------
$w = [15, 60, 40, 35, 40, 40];
$totalWidth = array_sum($w);

$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

// Encabezado tabla
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($startX);
$pdf->Cell($w[0], 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Plan', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Periodo', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Método', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Monto', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Fecha', 1, 1, 'C', true);

// Filasd
$pdf->SetFont('helvetica', '', 10);
$relleno = false;

if (count($pagos) > 0) {
    foreach ($pagos as $p) {
        $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
        $plan = $p['plan'] ?: '—';
        $periodo = ucfirst($p['periodo'] ?: '—');
        $metodo = ucfirst($p['metodo']);
        $monto = '$' . number_format($p['monto'], 2, ',', '.');
        $fecha = date('d/m/Y H:i', strtotime($p['fecha']));

        $pdf->SetX($startX);
        $pdf->Cell($w[0], 8, $p['id'], 1, 0, 'C', true);
        $pdf->Cell($w[1], 8, $plan, 1, 0, 'L', true);
        $pdf->Cell($w[2], 8, $periodo, 1, 0, 'C', true);
        $pdf->Cell($w[3], 8, $metodo, 1, 0, 'C', true);
        $pdf->Cell($w[4], 8, $monto, 1, 0, 'R', true);
        $pdf->Cell($w[5], 8, $fecha, 1, 1, 'C', true);

        $relleno = !$relleno;
    }
} else {
    $pdf->SetX($startX);
    $pdf->Cell($totalWidth, 10, 'No se encontraron pagos para este miembro.', 1, 1, 'C');
}

$pdf->Output('historial_pagos.pdf', 'I');
?>
