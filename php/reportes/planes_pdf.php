<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/librerias/tcpdf/tcpdf.php';

// CLASE PERSONALIZADA
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
        $this->Cell(0, 10, 'Reporte de Planes', 0, 1, 'C');
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
$periodo = $_GET['periodo'] ?? '';
$activo = $_GET['activo'] ?? '';

$sql = "SELECT * FROM planes WHERE 1";

if ($busqueda !== '') {
    $busqueda = $conexion->real_escape_string($busqueda);
    $sql .= " AND nombre LIKE '%$busqueda%'";
}
if ($periodo && in_array($periodo, ['mensual', 'anual'])) {
    $sql .= " AND periodo = '$periodo'";
}
if ($activo !== '') {
    $activo = (int)$activo;
    $sql .= " AND activo = $activo";
}

$sql .= " ORDER BY id DESC";
$result = $conexion->query($sql);
$planes = $result->fetch_all(MYSQLI_ASSOC);

// CREAR PDF
$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Listado de Planes');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
if ($periodo) {
    $pdf->Cell(0, 8, 'Periodo: ' . ucfirst($periodo), 0, 1);
}
if ($activo !== '') {
    $pdf->Cell(0, 8, 'Estado: ' . ($activo ? 'Activos' : 'Inactivos'), 0, 1);
}
if ($busqueda !== '') {
    $pdf->Cell(0, 8, 'Búsqueda: ' . $busqueda, 0, 1);
}
$pdf->Ln(5);

// TABLA CENTRADA
$w = [12, 65, 35, 35, 30, 45];
$totalWidth = array_sum($w);
$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($startX);
$pdf->Cell($w[0], 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Periodo', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Activo', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Creado en', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);
$relleno = false;

foreach ($planes as $p) {
    $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
    $pdf->SetX($startX);

    $activo_txt = $p['activo'] ? '✔️ Sí' : '✖️ No';
    $precio = '$' . number_format($p['precio'], 2, ',', '.');
    $creado = date('d/m/Y H:i', strtotime($p['creado_en']));

    $pdf->Cell($w[0], 8, $p['id'], 1, 0, 'C', true);
    $pdf->Cell($w[1], 8, $p['nombre'], 1, 0, 'L', true);
    $pdf->Cell($w[2], 8, ucfirst($p['periodo']), 1, 0, 'C', true);
    $pdf->Cell($w[3], 8, $precio, 1, 0, 'R', true);
    $pdf->Cell($w[4], 8, $activo_txt, 1, 0, 'C', true);
    $pdf->Cell($w[5], 8, $creado, 1, 1, 'C', true);
    $relleno = !$relleno;
}

if (count($planes) == 0) {
    $pdf->SetX($startX);
    $pdf->Cell($totalWidth, 10, 'No se encontraron planes con los filtros aplicados.', 1, 1, 'C');
}

$pdf->Output('planes.pdf', 'I');
?>
