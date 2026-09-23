<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';

$usuario_id = isset($_GET['usuario_id']) ? (int) $_GET['usuario_id'] : 0;
if ($usuario_id <= 0) {
  header("Location: /gymember/php/crud/miembros/miembros.php");
  exit;
}

// Datos del usuario
$sql_user = "SELECT nombre, apellido, email, telefono FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$user_res = $stmt->get_result();
$usuario = $user_res->fetch_assoc();
$stmt->close();

if (!$usuario) {
  header("Location: /gymember/php/crud/miembros/miembros.php");
  exit;
}

$sql_pagos = "
  SELECT p.id, p.metodo, p.monto, p.fecha, pl.nombre AS plan, pl.periodo
  FROM pagos p
  LEFT JOIN planes pl ON p.plan_id = pl.id
  WHERE p.usuario_id = ?
  ORDER BY p.fecha DESC
";
$stmt = $conn->prepare($sql_pagos);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$pagos_res = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Historial de Pagos</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <div class="container">
    <div class="formulario" style="max-width:600px;">
      <h1 class="titulo" style="text-align:center;">Historial de Pagos</h1>
      <div style="text-align:center; margin-bottom:20px;">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
      </div>

      <div class="acciones" style="justify-content: flex-end; margin-bottom:15px;">
        <a href="/gymember/php/reportes/historial_pagos_pdf.php?usuario_id=<?= $usuario_id ?>" target="_blank" class="btn solid">Exportar PDF</a>
        <a href="/gymember/php/crud/miembros/ver_miembro.php?id=<?= $usuario_id ?>" class="btn outline">Volver</a>
      </div>

      <?php if ($pagos_res && $pagos_res->num_rows > 0): ?>
        <table class="tabla">
          <thead>
            <tr>
              <th>ID Pago</th>
              <th>Plan</th>
              <th>Método</th>
              <th>Monto</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $pagos_res->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['plan'] ?: '-') ?> (<?= ucfirst($row['periodo'] ?: '—') ?>)</td>
                <td><?= ucfirst(htmlspecialchars($row['metodo'])) ?></td>
                <td>$<?= number_format($row['monto'], 2, ',', '.') ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted" style="text-align:center;">Este miembro aún no tiene pagos registrados.</p>
      <?php endif; ?>
    </div>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
