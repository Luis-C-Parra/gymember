<?php
require_once __DIR__ . '/../../../config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: planes.php");
  exit;
}

$q = $conexion->prepare("SELECT * FROM planes WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$plan = $q->get_result()->fetch_assoc();

function render_beneficios($plan) {
  echo '<ul class="beneficios">';
  for ($i = 1; $i <= 6; $i++) {
    $texto = trim($plan["item{$i}_texto"] ?? '');
    if ($texto === '') continue;
    $icon = ($plan["item{$i}_icono"] === 'x') ? 'mdi:close-circle' : 'mdi:check-circle';
    echo "<li><iconify-icon icon=\"$icon\"></iconify-icon> " . htmlspecialchars($texto) . "</li>";
  }
  echo '</ul>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <!-- Título -->
  <title>GYMember | Ver Plan</title>

  <!-- Ícono y fuentes -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container ver-plan">
    <h1 class="titulo">Detalles del Plan</h1>

    <div class="card secundon">
      <?php if (stripos($plan['nombre'], 'full') !== false): ?>
        <div class="etiqueta">Oferta especial</div>
      <?php endif; ?>

      <h3 class="titulo"><?= htmlspecialchars($plan['nombre']) ?></h3>
      <p class="precio">$<?= number_format($plan['precio'], 0, ',', '.') ?></p>
      <p class="frecuencia"><?= ucfirst($plan['periodo']) ?><?= $plan['periodo'] === 'anual' ? ' <span class="ahorro">(Ahorra 20%)</span>' : '' ?></p>

      <?php render_beneficios($plan); ?>

      <div class="acciones-centro">
        <a href="planes.php" class="btn outline">← Volver</a>
        <a href="editar_plan.php?id=<?= $plan['id'] ?>" class="btn solid">Editar</a>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
