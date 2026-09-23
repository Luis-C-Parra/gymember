<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/librerias/tcpdf/tcpdf.php';

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
        $this->Cell(0, 10, 'Reporte de Inventario de Tienda', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $fecha = date('d/m/Y H:i');
        $this->Cell(0, 10, "Generado el $fecha - Página " . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$busqueda = trim($_GET['q'] ?? '');
$estado_filtro = $_GET['estado'] ?? '';

if (!isset($conn) || $conn->connect_error) {
    die('Error de conexión a la base de datos: ' . $conn->connect_error);
}

$sql = "
    SELECT id, nombre, descripcion, precio, stock, categoria, activo
    FROM productos
    WHERE 1
";

if ($busqueda !== '') {
    $busqueda_safe = $conn->real_escape_string($busqueda);
    $sql .= " AND (nombre LIKE '%$busqueda_safe%' OR descripcion LIKE '%$busqueda_safe%' OR categoria LIKE '%$busqueda_safe%')";
}
if ($estado_filtro !== '') {
    $estado_int = ($estado_filtro === 'activo') ? 1 : 0;
    $sql .= " AND activo = '$estado_int'";
}
$sql .= " ORDER BY id ASC";

$result = $conn->query($sql);

if ($result === false) {
    die("Error en la Consulta SQL: " . $conn->error);
}

$productos = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();

$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Reporte de Productos');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
if ($estado_filtro) {
    $pdf->Cell(0, 8, 'Estado filtrado: ' . ucfirst($estado_filtro), 0, 1);
}
if ($busqueda !== '') {
    $pdf->Cell(0, 8, 'Búsqueda: ' . $busqueda, 0, 1);
}
$pdf->Ln(5);

$w = [15, 60, 40, 30, 20, 20, 70]; 
$totalWidth = array_sum($w);

$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($startX);
$pdf->Cell($w[0], 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Categoría', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Precio', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Stock', 1, 0, 'C', true);
$pdf->Cell($w[5], 8, 'Estado', 1, 0, 'C', true);
$pdf->Cell($w[6], 8, 'Descripción', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 9);
$relleno = false;

foreach ($productos as $p) {
    $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
    
    $nombre = $p['nombre'];
    $categoria = ucfirst($p['categoria']);
    $precio = '$' . number_format($p['precio'], 2, ',', '.');
    $stock = $p['stock'];
    $estado = $p['activo'] == 1 ? 'Activo' : 'Inactivo';
    $descripcion = $p['descripcion'];
    
    $altura_max = $pdf->getStringHeight($w[6], $descripcion, false, true, '', 1) + 2; 
    $altura_celda = max(8, $altura_max);

    $pdf->SetX($startX);

    $startY = $pdf->GetY(); 
    $startX_Cell = $pdf->GetX();

    $pdf->Cell($w[0], $altura_celda, $p['id'], 1, 0, 'C', $relleno, '', 0, false, 'T', 'M');
    $pdf->Cell($w[1], $altura_celda, $nombre, 1, 0, 'L', $relleno, '', 0, false, 'T', 'M');
    $pdf->Cell($w[2], $altura_celda, $categoria, 1, 0, 'L', $relleno, '', 0, false, 'T', 'M');
    $pdf->Cell($w[3], $altura_celda, $precio, 1, 0, 'R', $relleno, '', 0, false, 'T', 'M');
    $pdf->Cell($w[4], $altura_celda, $stock, 1, 0, 'C', $relleno, '', 0, false, 'T', 'M');
    $pdf->Cell($w[5], $altura_celda, $estado, 1, 0, 'C', $relleno, '', 0, false, 'T', 'M');
    
    $pdf->SetX($startX_Cell + array_sum(array_slice($w, 0, 6))); 
    
    $pdf->MultiCell($w[6], $altura_celda, $descripcion, 1, 'L', $relleno, 1, '', '', true, 0, false, true, $altura_celda, 'M');

    $relleno = !$relleno;
}

if (count($productos) == 0) {
    $pdf->SetX($startX);
    $pdf->Cell($totalWidth, 10, 'No se encontraron productos con los filtros aplicados.', 1, 1, 'C');
}

$pdf->Output('reporte_productos.pdf', 'I');
?>