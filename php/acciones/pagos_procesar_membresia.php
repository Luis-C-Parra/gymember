<?php
session_start();

// Conexión correcta
include('../../config/conexion.php');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso no permitido.");
}

// ---------------------------------------------
// 1) IDENTIFICAR O CREAR USUARIO
// ---------------------------------------------
$usuario_id = 0;
$usuario_email = "";
$usuario_nombre = "";

// Si está logueado
if (isset($_SESSION['usuario_id'])) {

    $usuario_id = $_SESSION['usuario_id'];
    $usuario_email = $_SESSION['email'] ?? "";
    $usuario_nombre = $_SESSION['nombre'] ?? "";

// Si es usuario nuevo
} elseif (isset($_POST['registro_nuevo'])) {

    $nombre  = trim($_POST['nombre'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $pass    = trim($_POST['password'] ?? '');
    $pass2   = trim($_POST['password_confirm'] ?? '');

if (!$nombre || !$email || !$usuario || !$pass) {
    echo "<script>
        alert('Faltan datos obligatorios.');
        window.location='../views/registro.php';
    </script>";
    exit;
}

if ($pass !== $pass2) {
    echo "<script>
        alert('Las contraseñas no coinciden.');
        window.location='../views/selec_plan.php';
    </script>";
    exit;
}


    // Contraseña en texto plano TEMPORAL
    $pass_plano = $pass;

    $stmt = $conn->prepare("
        INSERT INTO usuarios (usuario, contrasena, nombre, email, rol)
        VALUES (?, ?, ?, ?, 'miembro')
    ");
    
    if (!$stmt) die("Error prepare usuarios: " . $conn->error);

    $stmt->bind_param("ssss", $usuario, $pass_plano, $nombre, $email);

    if (!$stmt->execute()) die("Error al crear usuario: " . $stmt->error);

    $usuario_id = $stmt->insert_id;

    // Guardar en sesión
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = 'miembro';
    $_SESSION['nombre'] = $nombre;
    $_SESSION['email'] = $email;
    $_SESSION['usuario_id'] = $usuario_id;

    $usuario_email = $email;
    $usuario_nombre = $nombre;

    $stmt->close();

} else {
    die("No se pudo identificar al usuario.");
}


// ---------------------------------------------
// 2) VALIDAR DATOS DEL PLAN Y PAGO
// ---------------------------------------------
$plan_id = isset($_POST['plan_id']) ? (int) $_POST['plan_id'] : 0;
$monto   = isset($_POST['monto']) ? (float) $_POST['monto'] : 0;
$metodo  = $_POST['metodo_pago'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');

if ($plan_id <= 0)  die("Plan inválido.");
if ($monto <= 0)    die("Monto inválido.");
if (!in_array($metodo, ['efectivo','transferencia','tarjeta'])) die("Método inválido.");

// Obtener periodo del plan
$consultaPeriodo = $conn->query("SELECT nombre, periodo FROM planes WHERE id = $plan_id");
if (!$consultaPeriodo) die("Error obteniendo periodo: " . $conn->error);

$dataPlan = $consultaPeriodo->fetch_assoc();
$plan_nombre = $dataPlan['nombre'];
$periodo = $dataPlan['periodo'];

// Calcular fecha de vencimiento
if ($periodo === 'mensual') {
    $fecha_venc = date('Y-m-d', strtotime("$fecha_inicio +1 month"));
} else {
    $fecha_venc = date('Y-m-d', strtotime("$fecha_inicio +1 year"));
}


// ---------------------------------------------
// 3) CREAR MEMBRESÍA
// ---------------------------------------------
$stmt = $conn->prepare("
    INSERT INTO miembros (usuario_id, plan_id, estado, fecha_inicio, fecha_vencimiento, creado_en)
    VALUES (?, ?, 'activo', ?, ?, NOW())
");

if (!$stmt) die("Error prepare miembros: " . $conn->error);

$stmt->bind_param("iiss", $usuario_id, $plan_id, $fecha_inicio, $fecha_venc);

if (!$stmt->execute()) die("Error al crear membresía: " . $stmt->error);

$miembro_id = $stmt->insert_id;
$stmt->close();


// ---------------------------------------------
// 4) REGISTRAR EL PAGO
// ---------------------------------------------
$fecha_pago = date('Y-m-d H:i:s');

$stmt = $conn->prepare("
    INSERT INTO pagos (usuario_id, plan_id, monto, metodo, fecha, nota)
    VALUES (?, ?, ?, ?, ?, '')
");

if (!$stmt) die("Error prepare pagos: " . $stmt->error);

$stmt->bind_param("iidss", $usuario_id, $plan_id, $monto, $metodo, $fecha_pago);

if (!$stmt->execute()) die("Error al registrar el pago: " . $stmt->error);

$pago_id = $stmt->insert_id;
$stmt->close();


// ---------------------------------------------
// 5) GENERAR PDF DEL COMPROBANTE CON ESTILO CRUD
// =================================
require_once __DIR__ . '/../../librerias/tcpdf/tcpdf.php';

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
        $this->Cell(0, 10, 'Comprobante de Pago', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 9);
        $fecha = date('d/m/Y H:i');
        $this->Cell(0, 10, "Generado el $fecha - Página " . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDFGymember();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GYMember');
$pdf->SetTitle('Comprobante de Pago');
$pdf->SetMargins(10, 40, 10);
$pdf->AddPage();

// Tabla centrada
$w = [30, 60]; // ancho de columna: Campo, Valor
$totalWidth = array_sum($w);
$pageWidth = $pdf->getPageWidth();
$margins = $pdf->getMargins();
$usableWidth = $pageWidth - $margins['left'] - $margins['right'];
$startX = ($usableWidth - $totalWidth) / 2 + $margins['left'];

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(230, 230, 230);

// Datos del pago
$datos = [
    'Usuario' => $usuario_nombre,
    'Email' => $usuario_email,
    'Plan' => $plan_nombre,
    'Monto' => '$' . number_format($monto, 2, ',', '.'),
    'Método' => ucfirst($metodo),
    'Fecha de inicio' => $fecha_inicio,
    'Vencimiento' => $fecha_venc,
    'Fecha de pago' => $fecha_pago
];

$relleno = false;
foreach ($datos as $campo => $valor) {
    $pdf->SetFillColor($relleno ? 245 : 255, $relleno ? 245 : 255, $relleno ? 245 : 255);
    $pdf->SetX($startX);
    $pdf->Cell($w[0], 8, $campo, 1, 0, 'L', true);
    $pdf->Cell($w[1], 8, $valor, 1, 1, 'L', true);
    $relleno = !$relleno;
}

// Nombre del archivo PDF
$nombrePDF = "comprobante_$pago_id.pdf";

$rutaPDF = __DIR__ . "/../../comprobantes/" . $nombrePDF;

// Guardar archivo
$pdf->Output($rutaPDF, 'F');


// ---------------------------------------------
// 6) ENVIAR PDF POR EMAIL (PHPMailer)
// ---------------------------------------------
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../librerias/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../librerias/phpmailer/src/SMTP.php';
require __DIR__ . '/../../librerias/phpmailer/src/Exception.php';

// -------------------
// Validar email usuario
// -------------------
if (empty($usuario_email)) {
    die("Error: el usuario no tiene correo válido.");
}

// -------------------
// Validar que el PDF exista
// -------------------
if (!file_exists($rutaPDF)) {
    die("Error: no se encontró el PDF en $rutaPDF");
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "luciodamianflores@gmail.com"; 
    $mail->Password = "wlbeouicdzvzkomx"; // tu clave de aplicación, 16 caracteres sin espacios
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("luciodamianflores@gmail.com", "Gymember");
    $mail->addAddress($usuario_email, $usuario_nombre);

    $mail->Subject = "Comprobante de Membresia by GYMEMBER";
    $mail->Body    = "Hola $usuario_nombre, adjuntamos tu comprobante de pago.";

    $mail->addAttachment($rutaPDF);

    $mail->send();

    // Opcional: debug
    // echo "Correo enviado correctamente a $usuario_email";

} catch (Exception $e) {
    die("Error al enviar correo: " . $mail->ErrorInfo);
}



// ---------------------------------------------
// 7) REDIRIGIR
// ---------------------------------------------
header("Location: ../../index.php?success=1&pago_id=$pago_id");
exit;

?>
