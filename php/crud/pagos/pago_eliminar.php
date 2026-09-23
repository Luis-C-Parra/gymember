<?php
session_start();
include('../../../config/conexion.php'); 

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['mensaje'] = "❌ Error de conexión a la base de datos.";
    header('Location: admin_pagos.php');
    exit;
}

// Verificar si se recibió el ID por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: admin_pagos.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['mensaje'] = "❌ Error: ID de pago no válido.";
    header('Location: admin_pagos.php');
    exit;
}

// Ejecutar la sentencia DELETE
$stmt = $conn->prepare("DELETE FROM pagos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['mensaje'] = "🗑️ Pago ID: {$id} eliminado exitosamente.";
} else {
    $_SESSION['mensaje'] = "❌ Error al eliminar el pago: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirigir a la lista de pagos
header('Location: admin_pagos.php');
exit;
?>