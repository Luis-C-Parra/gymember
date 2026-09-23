<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$include_css = true; 

// PARÁMETROS DE FILTRO Y BUSCADOR 
$filtro_rol = $_GET['rol'] ?? '';
$busqueda = trim($_GET['q'] ?? '');

// CONSULTA BASE 
$sql = "SELECT * FROM usuarios WHERE 1";

// Filtro por rol
if ($filtro_rol && in_array($filtro_rol, ['admin','entrenador','miembro','invitado'])) {
  $sql .= " AND rol = '$filtro_rol'";
}

// Buscador por nombre, usuario o email
if ($busqueda !== '') {
  $busqueda = $conexion->real_escape_string($busqueda);
  $sql .= " AND (nombre LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' OR email LIKE '%$busqueda%')";
}

$sql .= " ORDER BY id DESC";
$query = $conexion->query($sql);
$usuarios = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>GYMember | Gestión de Usuarios</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">    

  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Gestión de Usuarios</h1>
    
    <div class="acciones acciones-usuarios">
      <form method="GET" class="filtros-form">
        <input type="text" name="q" placeholder="Buscar usuario o email..." value="<?= htmlspecialchars($busqueda) ?>" class="input-buscador">
        
        <select name="rol" onchange="this.form.submit()" class="select-filtro">
          <option value="">Todos los roles</option>
          <option value="admin" <?= $filtro_rol==='admin'?'selected':'' ?>>Admin</option>
          <option value="entrenador" <?= $filtro_rol==='entrenador'?'selected':'' ?>>Entrenador</option>
          <option value="miembro" <?= $filtro_rol==='miembro'?'selected':'' ?>>Miembro</option>
          <option value="invitado" <?= $filtro_rol==='invitado'?'selected':'' ?>>Invitado</option>
        </select>
        <button type="submit" class="btn outline">Filtrar</button>
      </form>

      <div>
        <a href="agregar_usuario.php" class="btn solid">+ Agregar Usuario</a>
        <a href="/gymember/php/reportes/usuarios_pdf.php?rol=<?= urlencode($filtro_rol) ?>&q=<?= urlencode($busqueda) ?>"
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
          <th>Usuario</th>
          <th>Rol</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Email</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($usuarios) > 0): ?>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><?= htmlspecialchars($u['usuario']) ?></td>
              <td><?= ucfirst($u['rol']) ?></td>
              <td><?= htmlspecialchars($u['nombre']) ?></td>
              <td><?= htmlspecialchars($u['apellido']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td class="acciones-tabla">
                <a href="ver_usuario.php?id=<?= $u['id'] ?>" class="btn outline">Ver</a>
                <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn outline">Editar</a>
                <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn outline btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted">No se encontraron usuarios.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
