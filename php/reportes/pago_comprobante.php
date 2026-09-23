<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/librerias/tcpdf/tcpdf.php';

$id = $_GET['id'] ?? 0;
if (!$id || !is_numeric($id)) {
    die('ID inválido');
}

$stmt = $conexion->prepare("
    SELECT 
        p.id, p.monto, p.metodo, p.fecha, p.nota,
        u.nombre AS nombre_usuario, u.email AS email_usuario,
        pl.nombre AS nombre_plan, pl.precio, pl.periodo
    FROM pagos p
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    LEFT JOIN planes pl ON p.plan_id = pl.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$p = $res->fetch_assoc();

if (!$p) {
    die('Pago no encontrado');
}

// INICIO PDF
class ComprobantePDF extends TCPDF {
    public function Header() {
        $logo = $_SERVER['DOCUMENT_ROOT'] . '/gymember/assets/img/imagotipo.png';
        if (file_exists($logo)) {
            $this->Image($logo, 15, 10, 35);
        }
        $this->SetFont('helvetica', 'B', 18);
        $this->Cell(0, 15, 'Comprobante de Pago', 0, 1, 'R');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $this->Cell(0, 10, "Página " . $this->getAliasNumPage() . " de " . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new ComprobantePDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 35, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// CONTENIDO
$html = '
<h2>Detalle del pago</h2>
<table border="1" cellpadding="6">
<tr><td><strong>N° de Pago:</strong></td><td>'.$p['id'].'</td></tr>
<tr><td><strong>Usuario:</strong></td><td>'.$p['nombre_usuario'].'</td></tr>
<tr><td><strong>Email:</strong></td><td>'.$p['email_usuario'].'</td></tr>
<tr><td><strong>Plan:</strong></td><td>'.$p['nombre_plan'].' ('.ucfirst($p['periodo']).')</td></tr>
<tr><td><strong>Método:</strong></td><td>'.ucfirst($p['metodo']).'</td></tr>
<tr><td><strong>Monto:</strong></td><td>$'.number_format($p['monto'],2,',','.').'</td></tr>
<tr><td><strong>Fecha:</strong></td><td>'.date("d/m/Y H:i", strtotime($p['fecha'])).'</td></tr>
';

if (!empty($p['nota'])) {
    $html .= '<tr><td><strong>Nota:</strong></td><td>'.nl2br($p['nota']).'</td></tr>';
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Ln(15);
$pdf->SetFont('helvetica', 'I', 11);
$pdf->Cell(0, 10, 'Gracias por confiar en GyMember', 0, 1, 'C');

$pdf->Output('comprobante_pago_'.$p['id'].'.pdf', 'I');
?>
