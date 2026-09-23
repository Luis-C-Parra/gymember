<?php
require_once __DIR__ . '/../../../config/conexion.php';

// ─────────────────────────────
// FILTROS
// ─────────────────────────────
$busqueda = trim($_GET['q'] ?? '');
$periodo = $_GET['periodo'] ?? '';
$activo = $_GET['activo'] ?? '';

$sql = "SELECT * FROM planes WHERE 1";

// Buscar por nombre
if ($busqueda !== '') {
  $busqueda = $conexion->real_escape_string($busqueda);
  $sql .= " AND nombre LIKE '%$busqueda%'";
}

// Filtrar por periodo
if ($periodo && in_array($periodo, ['mensual', 'anual'])) {
  $sql .= " AND periodo = '$periodo'";
}

// Filtrar por estado activo/inactivo
if ($activo !== '') {
  $activo = (int)$activo;
  $sql .= " AND activo = $activo";
}

$sql .= " ORDER BY id DESC";
$query = $conexion->query($sql);
$planes = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Gestión de Planes</title>

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
  <!-- Navbar -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Gestión de Planes</h1>

    <div class="acciones acciones-usuarios">
      <form method="GET" class="filtros-form">
        <input type="text" name="q" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($busqueda) ?>" class="input-buscador">

        <select name="periodo" class="select-filtro" onchange="this.form.submit()">
          <option value="">Todos los periodos</option>
          <option value="mensual" <?= $periodo === 'mensual' ? 'selected' : '' ?>>Mensual</option>
          <option value="anual" <?= $periodo === 'anual' ? 'selected' : '' ?>>Anual</option>
        </select>

        <select name="activo" class="select-filtro" onchange="this.form.submit()">
          <option value="">Todos los estados</option>
          <option value="1" <?= $activo === '1' ? 'selected' : '' ?>>Activo</option>
          <option value="0" <?= $activo === '0' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit" class="btn outline">Filtrar</button>
      </form>

      <div>
        <a href="agregar_plan.php" class="btn solid">+ Agregar Plan</a>
        <a href="/gymember/php/reportes/planes_pdf.php?q=<?= urlencode($busqueda) ?>&periodo=<?= urlencode($periodo) ?>&activo=<?= urlencode($activo) ?>"
          target="_blank"
          class="btn solid btn-icon"
          title="Exportar PDF">
          <iconify-icon icon="mdi:printer" width="20" height="20"></iconify-icon>
        </a>
      </div>
    </div>

    <table class="tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Periodo</th>
          <th>Precio</th>
          <th>Activo</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($planes) > 0): ?>
          <?php foreach ($planes as $p): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td><?= htmlspecialchars($p['nombre']) ?></td>
              <td><?= ucfirst($p['periodo']) ?></td>
              <td>$<?= number_format($p['precio'], 2, ',', '.') ?></td>
              <td><?= $p['activo'] ? '✔️' : '✖️' ?></td>
              <td><?= $p['creado_en'] ?></td>
              <td class="acciones-tabla">
                <a href="ver_plan.php?id=<?= $p['id'] ?>" class="btn outline">Ver</a>
                <a href="editar_plan.php?id=<?= $p['id'] ?>" class="btn outline">Editar</a>
                <a href="eliminar_plan.php?id=<?= $p['id'] ?>" class="btn outline btn-danger"
                   onclick="return confirm('¿Seguro que deseas eliminar este plan?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">No hay planes registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <!-- Footer -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
