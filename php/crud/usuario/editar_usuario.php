<!-- USUARIO | EDITAR USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: usuarios.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $query = $conexion->prepare("UPDATE usuarios SET usuario=?, rol=?, nombre=?, email=? WHERE id=?");
    $query->bind_param("ssssi", $usuario, $rol, $nombre, $email, $id);
    $query->execute();
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
  <title>Editar Usuario</title>
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
  <div class="container">
    <h1 class="titulo">Editar Usuario</h1>
    <form class="formulario" method="POST">
      <label>Usuario:</label>
      <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>

      <label>Rol:</label>
      <select name="rol" required>
        <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="entrenador" <?= $usuario['rol'] == 'entrenador' ? 'selected' : '' ?>>Entrenador</option>
        <option value="miembro" <?= $usuario['rol'] == 'miembro' ? 'selected' : '' ?>>Miembro</option>
        <option value="invitado" <?= $usuario['rol'] == 'invitado' ? 'selected' : '' ?>>Invitado</option>
      </select>


      <label>Nombre completo:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">

      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar Cambios</button>
        <a href="usuarios.php" class="btn outline">Cancelar</a>
      </div>
    </form>
  </div>
</body>
</html>
