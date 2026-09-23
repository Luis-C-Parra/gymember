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
        $this->Cell(0, 10, 'Reporte de Usuarios', 0, 1, 'C');
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
$filtro_rol = $_GET['rol'] ?? '';
$busqueda = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM usuarios WHERE 1";
if ($filtro_rol && in_array($filtro_rol, ['admin','entrenador','miembro','invitado'])) {
  $sql .= " AND rol = '$filtro_rol'";
}
if ($busqueda !== '') {
  $busqueda = $conexion->real_escape_string($busqueda);
  $sql .= " AND (nombre LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' OR email LIKE '%$busqueda%')";
}
$sql .= " ORDER BY id DESC";
$query = $conexion->query($sql);
$usuarios = $query->fetch_all(MYSQLI_ASSOC);

// CREAR PDF
$pdf = new PDFGymember('L', 'mm', 'A4');
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Listado de Usuarios');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
if ($filtro_rol) {
  $pdf->Cell(0, 8, 'Rol filtrado: ' . ucfirst($filtro_rol), 0, 1);
}
if ($busqueda !== '') {
  $pdf->Cell(0, 8, 'Búsqueda: ' . $busqueda, 0, 1);
}
$pdf->Ln(5);

// TABLA
$w = [15, 45, 30, 70, 100]; 
$totalWidth = array_sum($w);

$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetX($startX);
$pdf->Cell($w[0], 8, 'ID', 1, 0, 'C', true);
$pdf->Cell($w[1], 8, 'Usuario', 1, 0, 'C', true);
$pdf->Cell($w[2], 8, 'Rol', 1, 0, 'C', true);
$pdf->Cell($w[3], 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell($w[4], 8, 'Email', 1, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);
$relleno = false;

foreach ($usuarios as $u) {
  $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
  $nombre = substr($u['nombre'], 0, 40) . (strlen($u['nombre']) > 40 ? '...' : '');
  $email = substr($u['email'], 0, 45) . (strlen($u['email']) > 45 ? '...' : '');

  $pdf->SetX($startX);
  $pdf->Cell($w[0], 8, $u['id'], 1, 0, 'C', true);
  $pdf->Cell($w[1], 8, $u['usuario'], 1, 0, 'L', true);
  $pdf->Cell($w[2], 8, ucfirst($u['rol']), 1, 0, 'C', true);
  $pdf->Cell($w[3], 8, $nombre, 1, 0, 'L', true);
  $pdf->Cell($w[4], 8, $email, 1, 1, 'L', true);
  $relleno = !$relleno;
}

if (count($usuarios) == 0) {
  $pdf->SetX($startX);
  $pdf->Cell($totalWidth, 10, 'No se encontraron usuarios con los filtros aplicados.', 1, 1, 'C');
}

// SALIDA FINAL
$pdf->Output('usuarios.pdf', 'I');
?>
