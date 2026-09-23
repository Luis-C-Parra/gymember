<?php
session_start();
include('../../../config/conexion.php'); 

if (!isset($conn) || $conn->connect_error) {
    $_SESSION['mensaje'] = "❌ Error de conexión a la base de datos.";
    header('Location: admin_pagos.php');
    exit;
}

$accion = $_POST['accion'] ?? '';
$id = (int)($_POST['id'] ?? 0); // Solo para 'editar'

// Obtener datos xd
$usuario_id = (int)($_POST['usuario_id'] ?? 0);
$plan_id = (int)($_POST['plan_id'] ?? 0);
$monto = (float)($_POST['monto'] ?? 0.0);
$metodo = $conn->real_escape_string($_POST['metodo'] ?? '');
$nota = $conn->real_escape_string($_POST['nota'] ?? '');
$errores = [];

// Validaciones
if ($usuario_id <= 0) $errores[] = "Debe seleccionar un usuario válido.";
if ($plan_id <= 0) $errores[] = "Debe seleccionar un plan válido.";
if ($monto <= 0) $errores[] = "El monto debe ser mayor a cero.";
if (!in_array($metodo, ['efectivo', 'transferencia', 'tarjeta'])) $errores[] = "Método de pago no válido.";

// Si hay errores, volver al formulario
if (!empty($errores)) {
    $_SESSION['mensaje'] = "❌ Error(es) de validación:<br>" . implode("<br>", $errores);
    $_SESSION['datos_formulario'] = $_POST;
    $location = ($accion === 'editar') ? 'pago_editar.php?id=' . $id : 'pago_crear.php';
    header('Location: ' . $location);
    exit;
}

// Crear miembro - Auto
$stmt = $conn->prepare("SELECT id FROM miembros WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$miembro = $resultado->fetch_assoc();
$stmt->close();

if (!$miembro) {
    $stmt = $conn->prepare("INSERT INTO miembros (usuario_id, estado) VALUES (?, 'activo')");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $miembro_id = $stmt->insert_id;
    $stmt->close();
} else {
    $miembro_id = $miembro['id'];
}

// Creación de pago
if ($accion === 'crear') {
    $stmt = $conn->prepare("
        INSERT INTO pagos (usuario_id, plan_id, monto, metodo, nota, fecha)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iidss", $usuario_id, $plan_id, $monto, $metodo, $nota);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Pago registrado exitosamente.";
        
        $check = $conn->prepare("SELECT id FROM miembros WHERE usuario_id = ?");
        $check->bind_param("i", $usuario_id);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows === 0) {
            $insert_miembro = $conn->prepare("
                INSERT INTO miembros (usuario_id, plan_id, fecha_inicio)
                VALUES (?, ?, NOW())
            ");
            $insert_miembro->bind_param("ii", $usuario_id, $plan_id);
            $insert_miembro->execute();
            $insert_miembro->close();
        }
        $check->close();

    } else {
        $_SESSION['mensaje'] = "❌ Error al registrar el pago: " . $stmt->error;
    }
    $stmt->close();
}

// LÓGICA DE ACTUALIZACIÓN
else if ($accion === 'editar') {
    if ($id <= 0) {
        $_SESSION['mensaje'] = "❌ Error: ID de pago no válido.";
        header('Location: admin_pagos.php');
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE pagos 
        SET usuario_id = ?, plan_id = ?, monto = ?, metodo = ?, nota = ?
        WHERE id = ?
    ");
    $stmt->bind_param("iidssi", $usuario_id, $plan_id, $monto, $metodo, $nota, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Pago actualizado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar el pago: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
header('Location: admin_pagos.php');
exit;
?>