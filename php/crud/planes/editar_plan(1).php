<?php
require_once __DIR__ . '/../../../config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: planes.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $periodo = $_POST['periodo'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $items = [];
    for ($i = 1; $i <= 6; $i++) {
        $items["item{$i}_texto"] = $_POST["item{$i}_texto"] ?? '';
        $items["item{$i}_icono"] = $_POST["item{$i}_icono"] ?? 'check';
    }

    // Array con todos los parámetros en orden
    $params = [
        $nombre, $precio, $periodo, $activo,
        $items['item1_texto'], $items['item1_icono'],
        $items['item2_texto'], $items['item2_icono'],
        $items['item3_texto'], $items['item3_icono'],
        $items['item4_texto'], $items['item4_icono'],
        $items['item5_texto'], $items['item5_icono'],
        $items['item6_texto'], $items['item6_icono'],
        $id
    ];

    // Generar automáticamente la cadena de tipos según cada parámetro
    $types = '';
    foreach ($params as $p) {
        if (is_int($p)) $types .= 'i';
        elseif (is_double($p)) $types .= 'd';
        else $types .= 's';
    }

    $q = $conexion->prepare("
        UPDATE planes SET
            nombre=?, precio=?, periodo=?, activo=?,
            item1_texto=?, item1_icono=?, item2_texto=?, item2_icono=?,
            item3_texto=?, item3_icono=?, item4_texto=?, item4_icono=?,
            item5_texto=?, item5_icono=?, item6_texto=?, item6_icono=?
        WHERE id=?
    ");

    // Bind dinámico usando operador splat (...)
    $q->bind_param($types, ...$params);
    $q->execute();

    header("Location: planes.php");
    exit;
}

// SELECT con bind dinámico
$q = $conexion->prepare("SELECT * FROM planes WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$plan = $q->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <!-- Título -->
  <title>GYMember | Editar Plan</title>

  <!-- Ícono y fuentes -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Editar Plan</h1>

    <form class="formulario" method="POST">
      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($plan['nombre']) ?>" required>

      <label>Precio:</label>
      <input type="number" step="0.01" name="precio" value="<?= $plan['precio'] ?>" required>

      <label>Periodo:</label>
      <select name="periodo">
        <option value="mensual" <?= $plan['periodo'] === 'mensual' ? 'selected' : '' ?>>Mensual</option>
        <option value="anual" <?= $plan['periodo'] === 'anual' ? 'selected' : '' ?>>Anual</option>
      </select>

      <div class="checklinea">
        <label for="activo">Activo:</label>
        <label class="switch">
          <input type="checkbox" id="activo" name="activo" <?= $plan['activo'] ? 'checked' : '' ?>>
          <span class="slider round"></span>
        </label>
      </div>

      <h3 class="subtitulo">Beneficios</h3>
      <div class="beneficios-grid">
        <?php for ($i = 1; $i <= 6; $i++): ?>
          <div class="beneficio-row">
            <label>Item <?= $i ?>:</label>
            <div class="beneficio-campo">
              <select name="item<?= $i ?>_icono" class="icono-select">
                <option value="check" <?= ($plan["item{$i}_icono"] ?? '') === 'check' ? 'selected' : '' ?>>✔</option>
                <option value="x" <?= ($plan["item{$i}_icono"] ?? '') === 'x' ? 'selected' : '' ?>>✖</option>
              </select>
              <input type="text" name="item<?= $i ?>_texto"
                     value="<?= htmlspecialchars($plan["item{$i}_texto"] ?? '') ?>"
                     placeholder="Descripción del ítem <?= $i ?>">
            </div>
          </div>
        <?php endfor; ?>
      </div>

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar cambios</button>
        <a href="planes.php" class="btn outline">Cancelar</a>
      </div>
    </form>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>