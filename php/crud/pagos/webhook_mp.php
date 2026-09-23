<?php
// webhook_mp.php — Capturador del retorno de Mercado Pago y automatización del Backend
include('../../../config/conexion.php'); 

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

session_start();

// 1. Mercado Pago inyecta automáticamente el estado en la URL de retorno
$status_mp = $_GET['status'] ?? $_GET['collection_status'] ?? '';

// 2. Verificamos que el pago sea exitoso y que contenga nuestra referencia empaquetada
if (($status_mp === 'success' || $status_mp === 'approved' || empty($status_mp)) && isset($_GET['external_reference'])) {
    
    $raw_reference = $_GET['external_reference']; // Ejemplo recibido: "12-3-15000.00"
    
    // Desarmamos la cadena usando el guion medio como separador
    $parts = explode("-", $raw_reference);
    
    if (count($parts) === 3) {
        $usuario_id = intval($parts[0]);
        $plan_id    = intval($parts[1]);
        $monto      = floatval($parts[2]);
    } else {
        $_SESSION['mensaje'] = "❌ Error: Estructura de referencia corrupta o incompleta.";
        header("Location: pago_crear.php");
        exit;
    }
    
    $fecha_pago = date('Y-m-d H:i:s');
    $metodo = "Tarjeta Débito (Mercado Pago)";
    $nota_automatica = "Pago electrónico automatizado e impactado de forma atómica en el mostrador.";

    // 3. INICIAR TRANSACCIÓN ATÓMICA DE BASE DE DATOS (Principio ACID de integridad fiduciaria)
    $conn->begin_transaction();

    try {
        // AUTOMATIZACIÓN A: Actualizar de forma automática el estado del miembro a "activo"
        $sql_update_miembro = "UPDATE miembros SET estado = 'activo', plan_id = $plan_id WHERE usuario_id = $usuario_id";
        if (!$conn->query($sql_update_miembro)) {
            throw new Exception("Error al actualizar el estado del miembro: " . $conn->error);
        }

        // AUTOMATIZACIÓN B: Asentar la transacción financiera en el flujo de caja contable
        $sql_insert_pago = "INSERT INTO pagos (usuario_id, plan_id, monto, metodo, nota, fecha) 
                            VALUES ($usuario_id, $plan_id, $monto, '$metodo', '$nota_automatica', '$fecha_pago')";
        
        if (!$conn->query($sql_insert_pago)) {
            throw new Exception("Error al registrar el movimiento contable en caja: " . $conn->error);
        }

        // 4. SI AMBAS OPERACIONES LOGRARON EJECUTARSE, CONGELAMOS LOS CAMBIOS REALES (Commit)
        $conn->commit();

        $_SESSION['mensaje'] = "✅ ¡Pago por Mercado Pago registrado con éxito! El miembro ha sido automatizado a estado Activo.";
        header("Location: admin_pagos.php");
        exit;

    } catch (Exception $e) {
        // SEGURIDAD CRÍTICA: Si el SQL falló, se revierte todo (Rollback) para evitar descalces financieros
        $conn->rollback();
        $_SESSION['mensaje'] = "❌ Error crítico de consistencia en el Backend: " . $e->getMessage();
        header("Location: pago_crear.php");
        exit;
    }

} else {
    $_SESSION['mensaje'] = "❌ Intento de acceso inválido o transacción no aprobada por Mercado Pago.";
    header("Location: pago_crear.php");
    exit;
}
?>