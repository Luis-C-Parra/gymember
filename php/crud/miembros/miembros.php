<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';

// Controlador Embebido: Procesar cambio de estado de membresía (Acción del Botón)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $id_miembro = intval($_POST['miembro_id']);
    $estado_actual = $_POST['estado_actual'];
    
    // Si está activo, lo suspendemos. Si está suspendido o inactivo, lo activamos.
    $nuevo_estado = ($estado_actual === 'activo') ? 'suspendido' : 'activo';
    
    $update_sql = "UPDATE miembros SET estado = '$nuevo_estado' WHERE id = $id_miembro";
    if ($conexion->query($update_sql)) {
        // Redirección limpia para evitar reenvío de formulario
        $queryString = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
        header("Location: " . $_SERVER['PHP_SELF'] . $queryString);
        exit;
    }
}

// Filtros
$busqueda = trim($_GET['q'] ?? '');
$estado = $_GET['estado'] ?? '';

$sql = "
  SELECT m.*, u.nombre, u.apellido, u.email, p.nombre AS plan_nombre
  FROM miembros m
  JOIN usuarios u ON m.usuario_id = u.id
  LEFT JOIN planes p ON m.plan_id = p.id
  WHERE 1
";

if ($busqueda !== '') {
  $busqueda = $conexion->real_escape_string($busqueda);
  $sql .= " AND (u.nombre LIKE '%$busqueda%' OR u.apellido LIKE '%$busqueda%' OR u.email LIKE '%$busqueda%')";
}

if ($estado && in_array($estado, ['activo','inactivo','suspendido'])) {
  $sql .= " AND m.estado = '$estado'";
}

$sql .= " ORDER BY m.id DESC";
$result = $conexion->query($sql);
$miembros = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Gestión de Miembros</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <style>
    .status-activo { color: #28a745; font-weight: bold; }       /* Verde */
    .status-inactivo { color: #dc3545; font-weight: bold; }     /* Rojo */
    .status-suspendido { color: #dc3545; font-weight: bold; }   /* Rojo */
    .btn-status-toggle { width: 100px; text-align: center; cursor: pointer; }
  </style>
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Gestión de Miembros</h1>

    <div class="acciones acciones-usuarios">
      <form method="GET" class="filtros-form">
        <input type="text" name="q" placeholder="Buscar nombre o email..." value="<?= htmlspecialchars($busqueda) ?>" class="input-buscador">
        <select name="estado" class="select-filtro" onchange="this.form.submit()">
          <option value="">Todos los estados</option>
          <option value="activo" <?= $estado==='activo'?'selected':'' ?>>Activo</option>
          <option value="inactivo" <?= $estado==='inactivo'?'selected':'' ?>>Inactivo</option>
          <option value="suspendido" <?= $estado==='suspendido'?'selected':'' ?>>Suspendido</option>
        </select>
        <button type="submit" class="btn outline">Filtrar</button>
      </form>
      <a href="/gymember/php/crud/usuario/agregar_usuario.php" class="btn solid">+ Agregar Usuario</a>
      <a href="/gymember/php/reportes/miembros_pdf.php?q=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>"
        target="_blank"
        class="btn solid btn-icon"
        title="Exportar PDF">
        <iconify-icon icon="mdi:printer" width="20" height="20"></iconify-icon>
      </a>
    </div>

    <table class="tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Plan</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($miembros) > 0): ?>
          <?php foreach ($miembros as $m): ?>
            <tr>
              <td><?= $m['id'] ?></td>
              <td><?= htmlspecialchars($m['nombre'] . ' ' . $m['apellido']) ?></td>
              <td><?= htmlspecialchars($m['email']) ?></td>
              <td><?= $m['plan_nombre'] ?: '—' ?></td>
              
              <td class="status-<?= $m['estado'] ?>">
                <?= ucfirst($m['estado']) ?>
              </td>
              
              <td class="acciones-tabla">
                <a href="ver_miembro.php?id=<?= $m['id'] ?>" class="btn outline">Ver</a>
                <a href="editar_miembro.php?id=<?= $m['id'] ?>" class="btn outline">Editar</a>
                
                <form method="POST" style="display: inline-block; margin: 0;">
                  <input type="hidden" name="miembro_id" value="<?= $m['id'] ?>">
                  <input type="hidden" name="estado_actual" value="<?= $m['estado'] ?>">
                  <input type="hidden" name="action" value="toggle_status">
                  
                  <?php if ($m['estado'] === 'activo'): ?>
                    <button type="submit" class="btn outline btn-status-toggle" style="color: #dc3545; border-color: #dc3545;" onclick="return confirm('¿Está seguro de que desea suspender a este miembro?')">Suspender</button>
                  <?php else: ?>
                    <button type="submit" class="btn outline btn-status-toggle" style="color: #28a745; border-color: #28a745;">Activar</button>
                  <?php endif; ?>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted">No se encontraron miembros.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>