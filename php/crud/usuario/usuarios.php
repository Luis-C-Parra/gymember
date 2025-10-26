<!-- USUARIO | GESTIÓN USUARIOS -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$include_css = true; 

$query = $conexion->query("SELECT * FROM usuarios ORDER BY id DESC");
$usuarios = $query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Título -->
  <title>GYMember | Gestión de Usuarios</title>

  <!-- Ícono -->
  <link rel="icon" href="assets/img/icono.ico">    
  
  <!-- CSS & Otros --> 
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
  <!-- NAVBAR -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Gestión de Usuarios</h1>
    <div class="acciones">
      <a href="agregar_usuario.php" class="btn solid">+ Agregar Usuario</a>
    </div>

    <table class="tabla">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Rol</th>
          <th>Nombre</th>
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
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td class="acciones-tabla">
                <a href="ver_usuario.php?id=<?= $u['id'] ?>" class="btn outline">Ver</a>
                <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn outline">Editar</a>
                <a href="eliminar_usuario.php?id=<?= $u['id'] ?>" class="btn outline btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <!-- FOOTER -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>

</body>
</html>
