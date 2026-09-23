<?php
// pago_ver.php
session_start();
include('../../../config/conexion.php');

$id = $_GET['id'] ?? 0;
if (!$id || !is_numeric($id)) {
    header('Location: admin_pagos.php');
    exit;
}

$stmt = $conn->prepare("SELECT 
        p.id, p.monto, p.metodo, p.fecha, p.nota,
        u.nombre AS nombre_usuario, u.email AS email_usuario,
        pl.nombre AS nombre_plan, pl.precio, pl.periodo
      FROM pagos p
      LEFT JOIN usuarios u ON p.usuario_id = u.id
      LEFT JOIN planes pl ON p.plan_id = pl.id
      WHERE p.id = ?");

$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$pago = $res->fetch_assoc();
$stmt->close();
$conn->close();

if (!$pago) {
  header('Location: admin_pagos.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GyMember | Ver Pago #<?= $pago['id'] ?></title>
  
  <!-- Ícono y fuentes -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/gymember/assets/css/styles.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'].'/gymember/php/includes/navbar.php'); ?>

  <main class="contenedor-crud">
    <h1 class="titulo">Detalle del Pago #<?= $pago['id'] ?></h1>

    <div class="ver-plan card">
      <h2><?= htmlspecialchars($pago['nombre_usuario']) ?></h2>
      <p><strong>Email:</strong> <?= htmlspecialchars($pago['email_usuario']) ?></p>
      <p><strong>Plan:</strong> <?= htmlspecialchars($pago['nombre_plan']) ?> (<?= ucfirst($pago['periodo']) ?>)</p>
      <p><strong>Método:</strong> <?= ucfirst($pago['metodo']) ?></p>
      <p><strong>Monto:</strong> $<?= number_format($pago['monto'], 2, ',', '.') ?></p>
      <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pago['fecha'])) ?></p>
      <?php if(!empty($pago['nota'])): ?>
        <p><strong>Nota:</strong><br><?= nl2br(htmlspecialchars($pago['nota'])) ?></p>
      <?php endif; ?>

      <div class="acciones-centro">
        <a href="/gymember/php/reportes/pago_comprobante.php?id=<?= $pago['id'] ?>"
          target="_blank"
          class="btn solid btn-icon imprimir-icon"
          title="Descargar comprobante" id="btn_pago_individual">
          <iconify-icon icon="mdi:file-pdf-box" width="20" height="20"></iconify-icon>
        </a>

        <a href="admin_pagos.php" class="btn solid">Volver</a>
        <a href="pago_editar.php?id=<?= $pago['id'] ?>" class="btn solid">Editar</a>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'].'/gymember/php/includes/footer.php'); ?>
</body>
</html>
