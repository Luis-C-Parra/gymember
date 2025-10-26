<!-- USUARIO | INFORMACIÓN DE USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
  header("Location: usuarios.php");
  exit;
}

$query = $conexion->prepare("SELECT * FROM usuarios WHERE id=?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver Usuario</title>
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
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
</body>
</html>
 
