<?php
session_start();
include('/gymember/config/conexion.php'); // Ajusta la ruta según tu estructura

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['mensaje'] = "❌ Error de conexión a la base de datos.";
    header('Location: selec_plan.php');
    exit;
}

// Detectar si es usuario logueado o nuevo
$usuarioLogueado = isset($_SESSION['usuario']);
$socio_id = 0;

// Registrar usuario nuevo si corresponde
if (!$usuarioLogueado && isset($_POST['registro_nuevo'])) {
    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $usuario  = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, usuario, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $usuario, $password);

    if ($stmt->execute()) {
        $socio_id = $stmt->insert_id;
        $_SESSION['usuario'] = [
            'id' => $socio_id,
            'nombre' => $nombre,
            'email' => $email,
            'usuario' => $usuario
        ];
    } else {
        $_SESSION['mensaje'] = "❌ Error al registrar usuario: " . $stmt->error;
        header('Location: selec_plan.php');
        exit;
    }
    $stmt->close();
} elseif ($usuarioLogueado) {
    $socio_id = $_SESSION['usuario']['id'];
} else {
    $_SESSION['mensaje'] = "❌ No se pudo identificar al usuario.";
    header('Location: selec_plan.php');
    exit;
}

// Obtener datos del pago
$monto  = (float)($_POST['monto'] ?? 0);
$metodo = $conn->real_escape_string($_POST['metodo_pago'] ?? '');

// ======================================
// Agregamos la fecha aquí (fecha de pago)
// ======================================
$fecha = date('Y-m-d'); // <-- Aquí se define la fecha que se guardará en la tabla

// Validaciones básicas
$errores = [];
if ($socio_id <= 0) $errores[] = "Usuario no válido.";
if ($monto <= 0) $errores[] = "Monto no válido.";
if (!in_array($metodo, ['efectivo', 'transferencia', 'tarjeta'])) $errores[] = "Método de pago no válido.";

if (!empty($errores)) {
    $_SESSION['mensaje'] = "❌ Error(s):<br>" . implode("<br>", $errores);
    header('Location: selec_plan.php');
    exit;
}

// Insertar pago en la tabla usando fecha en lugar de nota
$stmt = $conn->prepare("INSERT INTO pagos (socio_id, monto, metodo, fecha) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("❌ Error en prepare(): " . $conn->error);
}
$stmt->bind_param("idss", $socio_id, $monto, $metodo, $fecha); // <-- bind de fecha aquí
//comprobacion para ver si redirecciona bien
if ($stmt->execute()) {
    echo "✅ Pago registrado correctamente. ID: " . $stmt->insert_id;
    exit;
} else {
    die("❌ Error al registrar pago: " . $stmt->error);
}


if ($stmt->execute()) {
    $pago_id = $stmt->insert_id;
    $_SESSION['mensaje'] = "✅ Membresía y pago registrados correctamente.";
} else {
    $_SESSION['mensaje'] = "❌ Error al registrar el pago: " . $stmt->error;
    header('Location: selec_plan.php');
    exit;
}

$stmt->close();
$conn->close();

// Redirigir a comprobante
header('Location: views/selec_plan.php?pago_id=' . $pago_id);
exit;
?>
