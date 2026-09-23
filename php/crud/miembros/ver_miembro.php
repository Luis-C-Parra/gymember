<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: miembros.php");
  exit;
}

$sql = "
  SELECT m.*, u.nombre, u.apellido, u.email, u.telefono, u.foto, p.nombre AS plan_nombre, p.precio, p.periodo
  FROM miembros m
  JOIN usuarios u ON m.usuario_id = u.id
  LEFT JOIN planes p ON m.plan_id = p.id
  WHERE m.id = ?
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$miembro = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Ver Miembro</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <div class="container">
    <!-- INFO DEL MIEMBRO -->
    <div class="formulario">
      <h1 class="titulo" style="color: #FF2E88;">Detalles del Miembro</h1>
      <p><strong>Nombre:</strong> <?= htmlspecialchars($miembro['nombre'] . ' ' . $miembro['apellido']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($miembro['email']) ?></p>
      <p><strong>Teléfono:</strong> <?= htmlspecialchars($miembro['telefono']) ?></p>
      <p><strong>Estado:</strong> <?= ucfirst($miembro['estado']) ?></p>

      <?php if ($miembro['plan_id']): ?>
        <p><strong>Plan:</strong> <?= $miembro['plan_nombre'] ?> (<?= ucfirst($miembro['periodo']) ?> - $<?= number_format($miembro['precio'], 0, ',', '.') ?>)</p>
        <p><strong>Inicio:</strong> <?= $miembro['fecha_inicio'] ?: '-' ?></p>
        <p><strong>Vencimiento:</strong> <?= $miembro['fecha_vencimiento'] ?: '-' ?></p>
      <?php else: ?>
        <p class="text-muted">Sin plan asignado.</p>
      <?php endif; ?>

      <?php if ($miembro['foto']): ?>
        <p><strong>Foto:</strong></p>
        <img src="/gymember/uploads/<?= htmlspecialchars($miembro['foto']) ?>" width="100" style="border-radius:8px;">
      <?php endif; ?>

      <!-- BOTONES PRINCIPALES -->
      <div class="acciones" style="justify-content: center; gap:12px; margin-top:20px;">
        <a href="miembros.php" class="btn outline">Volver</a>
        <a href="editar_miembro.php?id=<?= $miembro['id'] ?>" class="btn solid">Editar</a>
      </div>
    </div>

    <!-- BOTONES DE PAGOS SEPARADOS -->
    <div class="acciones" style="margin-top:25px; justify-content: center; gap:12px; flex-wrap: wrap;">
      <a href="/gymember/php/crud/pagos/pago_crear.php?usuario_id=<?= $miembro['usuario_id'] ?>" class="btn solid">Registrar Pago</a>
      <a href="/gymember/php/crud/pagos/historial_pagos.php?usuario_id=<?= $miembro['usuario_id'] ?>" target="_blank" class="btn outline">Historial de Pagos</a>
    </div>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
