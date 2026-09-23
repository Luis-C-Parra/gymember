<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/librerias/tcpdf/tcpdf.php';

// CLASE 
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
        $this->Cell(0, 10, 'Reporte de Miembros', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $fecha = date('d/m/Y H:i');
        $this->Cell(0, 10, "Generado el $fecha - Página " . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// FILTROS Y CONSULTA
$busqueda = trim($_GET['q'] ?? '');
$estado = $_GET['estado'] ?? '';

$sql = "
    SELECT m.*, u.nombre, u.apellido, u.email, p.nombre AS plan_nombre
    FROM miembros m
    JOIN usuarios u ON m.usuario_id = u.id
    LEFT JOIN planes p ON m.plan_id = p.id
    WHERE 1
";

if ($busqueda !== '') {
    $busqueda = $conexion->real_escape_string($busqueda);
    $sql .= " AND (u.nombre LIKE '%$busqueda%' OR u.apellido LIKE '%$busqueda%' OR u.email LIKE '%$busqueda%')";
}
if ($estado && in_array($estado, ['activo', 'inactivo', 'suspendido'])) {
    $sql .= " AND m.estado = '$estado'";
}
$sql .= " ORDER BY m.id DESC";

$result = $conexion->query($sql);
$miembros = $result->fetch_all(MYSQLI_ASSOC);

// CREAR PDF
$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Listado de Miembros');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
if ($estado) {
    $pdf->Cell(0, 8, 'Estado filtrado: ' . ucfirst($estado), 0, 1);
}
if ($busqueda !== '') {
    $pdf->Cell(0, 8, 'Búsqueda: ' . $busqueda, 0, 1);
}
$pdf->Ln(5);

// TABLA 
$w = [12, 40, 70, 55, 30, 30];
$totalWidth = array_sum($w);

$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

// Encabezados
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($startX);
$pdf->Cell($w[0], 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Email', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Plan', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Estado', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Inicio', 1, 1, 'C', true);

// Filas
$pdf->SetFont('helvetica', '', 10);
$relleno = false;

foreach ($miembros as $m) {
    $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
    $nombre = substr($m['nombre'] . ' ' . $m['apellido'], 0, 35);
    $email = substr($m['email'], 0, 45);
    $plan = $m['plan_nombre'] ?: '—';
    $estado = ucfirst($m['estado']);
    $inicio = $m['fecha_inicio'] ?? '—';

    $pdf->SetX($startX);
    $pdf->Cell($w[0], 8, $m['id'], 1, 0, 'C', true);
    $pdf->Cell($w[1], 8, $nombre, 1, 0, 'L', true);
    $pdf->Cell($w[2], 8, $email, 1, 0, 'L', true);
    $pdf->Cell($w[3], 8, $plan, 1, 0, 'L', true);
    $pdf->Cell($w[4], 8, $estado, 1, 0, 'C', true);
    $pdf->Cell($w[5], 8, $inicio, 1, 1, 'C', true);
    $relleno = !$relleno;
}

if (count($miembros) == 0) {
    $pdf->SetX($startX);
    $pdf->Cell($totalWidth, 10, 'No se encontraron miembros con los filtros aplicados.', 1, 1, 'C');
}

$pdf->Output('miembros.pdf', 'I');
?>
