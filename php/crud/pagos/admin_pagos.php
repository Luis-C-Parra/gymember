<?php
// admin_pagos.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$include_css = true; 

if (!isset($conexion) && isset($conn)) $conexion = $conn;

if (!isset($conexion) || $conexion->connect_error) {
    die("Error de conexión a la base de datos.");
}

// Manejo de sesión y mensaje
session_start();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

// Parámetros de filtro
$busqueda = trim($_GET['q'] ?? '');
$filtro_metodo = $_GET['metodo'] ?? '';
$fecha_desde = $_GET['desde'] ?? '';
$fecha_hasta = $_GET['hasta'] ?? '';

// Consulta principal
$sql = "SELECT 
          p.id, p.monto, p.metodo, p.fecha, p.nota,
          u.id AS usuario_id, u.usuario AS usuario_login, 
          u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, 
          u.email AS email_usuario,
          pl.id AS plan_id, pl.nombre AS nombre_plan
        FROM pagos p
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        LEFT JOIN planes pl ON p.plan_id = pl.id
        WHERE 1=1";

// Filtro de búsqueda
if ($busqueda !== '') {
  $b = $conexion->real_escape_string($busqueda);
  $sql .= " AND (
              u.nombre LIKE '%$b%' OR
              u.apellido LIKE '%$b%' OR
              u.usuario LIKE '%$b%' OR
              u.email LIKE '%$b%' OR
              pl.nombre LIKE '%$b%'
            )";
}

// Filtro de método
$metodos_validos = ['efectivo', 'tarjeta', 'transferencia'];
if ($filtro_metodo !== '' && in_array($filtro_metodo, $metodos_validos)) {
  $m = $conexion->real_escape_string($filtro_metodo);
  $sql .= " AND p.metodo = '$m'";
}

// Filtro de fechas
if ($fecha_desde !== '') {
  $fd = $conexion->real_escape_string($fecha_desde) . " 00:00:00";
  $sql .= " AND p.fecha >= '$fd'";
}
if ($fecha_hasta !== '') {
  $fh = $conexion->real_escape_string($fecha_hasta) . " 23:59:59";
  $sql .= " AND p.fecha <= '$fh'";
}

$sql .= " ORDER BY p.fecha DESC";
$query = $conexion->query($sql);
$pagos = $query ? $query->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>GYMember | Gestión de Pagos</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Gestión de Pagos</h1>

    <div class="acciones acciones-usuarios" style="margin-bottom:18px; justify-content:space-between; align-items:center; flex-wrap:wrap;">
    
    <!-- FORM DE FILTROS -->
    <form method="GET" class="filtros-form" id="filtrosForm" 
            style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

        <input 
        type="text" 
        name="q" 
        placeholder="Buscar por usuario, email o plan..." 
        value="<?= htmlspecialchars($busqueda) ?>" 
        class="input-buscador"
        style="width:230px;"
        >

        <select name="metodo" id="metodo" class="select-filtro" style="width:150px;">
        <option value="">Método</option>
        <option value="Efectivo" <?= $filtro_metodo==='Efectivo'?'selected':'' ?>>Efectivo</option>
        <option value="Tarjeta de Debito" <?= $filtro_metodo==='Tarjeta de Debito'?'selected':'' ?>>Tarjeta</option>
        <option value="Transferencia" <?= $filtro_metodo==='Transferencia'?'selected':'' ?>>Transferencia</option>
        <option value="Mercado Pago" <?= $filtro_metodo==='Mercado Pago'?'selected':'' ?>>Mercado Pago</option>
        </select>

        <input type="date" name="desde" id="desde" value="<?= htmlspecialchars($fecha_desde) ?>" class="input-fecha" style="width:150px;">
        <input type="date" name="hasta" id="hasta" value="<?= htmlspecialchars($fecha_hasta) ?>" class="input-fecha" style="width:150px;">

        <button type="submit" class="btn outline" style="padding:8px 18px;">Filtrar</button>
        <a href="admin_pagos.php" class="btn" style="padding:8px 18px;">Limpiar</a>
        <a href="/gymember/php/reportes/pagos_pdf.php?metodo=<?= urlencode($filtro_metodo) ?>&q=<?= urlencode($busqueda) ?>&desde=<?= urlencode($fecha_desde) ?>&hasta=<?= urlencode($fecha_hasta) ?>"
   target="_blank"
   class="btn solid btn-icon"
   title="Exportar PDF">
  <iconify-icon icon="mdi:printer" width="20" height="20"></iconify-icon>
</a>
        
    </form>

    <!-- BOTÓN A LA DERECHA -->
    <a href="pago_crear.php" class="btn solid" style="white-space:nowrap;">+ Registrar Pago</a>
    
    <a href="/gymember/php/acciones/cron_vencimientos.php" target="_blank" class="btn outline">
   🔄 Forzar Control de Vencimientos (Test Cron)
</a>
          
    </div>

    <?php if ($mensaje): ?>
      <div class="<?= strpos($mensaje,'❌')!==false ? 'alerta-error' : 'alerta-exito' ?>">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>

    <table class="tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Plan</th>
          <th>Método</th>
          <th>Monto</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($pagos) > 0): ?>
          <?php foreach ($pagos as $p): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td><?= htmlspecialchars($p['nombre_usuario'] . ' ' . $p['apellido_usuario']) ?: htmlspecialchars($p['usuario_login']) ?></td>
              <td><?= htmlspecialchars($p['nombre_plan']) ?></td>
              <td><?= ucfirst(htmlspecialchars($p['metodo'])) ?></td>
              <td>$<?= number_format($p['monto'], 2, ',', '.') ?></td>
              <td><?= date('d/m/Y', strtotime($p['fecha'])) ?></td>
              <td class="acciones-tabla">
                <a href="pago_ver.php?id=<?= $p['id'] ?>" class="btn outline">Ver</a>
                <a href="pago_editar.php?id=<?= $p['id'] ?>" class="btn outline">Editar</a>
                <form action="pago_eliminar.php" method="POST" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn outline btn-danger" onclick="return confirm('¿Eliminar este pago?');">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">No se encontraron pagos.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>

  <script>
    // Auto-filtrar al cambiar método o fechas (sin presionar botón)
    const form = document.getElementById('filtrosForm');
    ['metodo','desde','hasta'].forEach(id => {
      document.getElementById(id)?.addEventListener('change', () => form.submit());
    });
  </script>
</body>
</html>
