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
        $this->Cell(0, 10, 'Reporte de Pagos', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $fecha = date('d/m/Y H:i');
        $this->Cell(0, 10, "Generado el $fecha - Página " . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// CONSULTA: TODOS LOS PAGOS
$sql = "SELECT 
            p.id, p.monto, p.metodo, p.fecha,
            u.nombre AS nombre_usuario,
            pl.nombre AS nombre_plan
        FROM pagos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        LEFT JOIN planes pl ON p.plan_id = pl.id
        ORDER BY p.fecha DESC";

$result = $conexion->query($sql);
$pagos = $result->fetch_all(MYSQLI_ASSOC);

// CREAR PDF
$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Listado de Pagos');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

// TABLA
$w = [12, 60, 60, 35, 30, 45]; // ancho de columnas
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
$pdf->Cell($w[1], 8, 'Usuario', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Plan', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Método', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Monto', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Fecha', 1, 1, 'C', true);

// Contenido
$pdf->SetFont('helvetica', '', 10);
$relleno = false;

foreach ($pagos as $p) {
    $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
    $pdf->SetX($startX);

    $monto = '$' . number_format($p['monto'], 2, ',', '.');
    $fecha = date('d/m/Y', strtotime($p['fecha']));

    $pdf->Cell($w[0], 8, $p['id'], 1, 0, 'C', true);
    $pdf->Cell($w[1], 8, $p['nombre_usuario'], 1, 0, 'L', true);
    $pdf->Cell($w[2], 8, $p['nombre_plan'], 1, 0, 'L', true);
    $pdf->Cell($w[3], 8, ucfirst($p['metodo']), 1, 0, 'C', true);
    $pdf->Cell($w[4], 8, $monto, 1, 0, 'R', true);
    $pdf->Cell($w[5], 8, $fecha, 1, 1, 'C', true);

    $relleno = !$relleno;
}

if (count($pagos) == 0) {
    $pdf->SetX($startX);
    $pdf->Cell($totalWidth, 10, 'No se encontraron pagos.', 1, 1, 'C');
}

// Salida
$pdf->Output('pagos.pdf', 'I');
?>
