<!-- USUARIO | INFORMACIÓN DE USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: usuarios.php");
  exit;
}

// Obtener usuario
$query = $conexion->prepare("SELECT * FROM usuarios WHERE id=?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$usuario = $result->fetch_assoc();

// Si no existe, redirige
if (!$usuario) {
  header("Location: usuarios.php");
  exit;
}

// Si es miembro, buscar su info en tabla miembros + plan
$miembro = null;
if ($usuario['rol'] === 'miembro') {
  $sql_miembro = "
    SELECT m.*, p.nombre AS plan_nombre, p.periodo, p.precio
    FROM miembros m
    LEFT JOIN planes p ON m.plan_id = p.id
    WHERE m.usuario_id = ?
  ";
  $stmt = $conexion->prepare($sql_miembro);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $miembro = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">

  <title>GYMember | Ver Usuario</title>

  <!-- Ícono -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">    
  
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
  <!-- NAVBAR -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <div class="container">
    <h1 class="titulo">Detalles del Usuario</h1>
    <div class="formulario">
      <p><strong>ID:</strong> <?= $usuario['id'] ?></p>
      <p><strong>Usuario:</strong> <?= htmlspecialchars($usuario['usuario']) ?></p>
      <p><strong>Rol:</strong> <?= ucfirst($usuario['rol']) ?></p>
      <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
      <div class="acciones">
        <a href="usuarios.php" class="btn outline">Volver</a>
        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn solid">Editar</a>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>

</body>
</html>
 
