<!-- USUARIO | AGREGAR USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $query = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, rol, nombre, email) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $usuario, $contrasena, $rol, $nombre, $email);

    if ($query->execute()) {
        header("Location: usuarios.php");
        exit;
    } else {
        $error = "Error al agregar usuario: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Usuario</title>
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>

<body>
  <div class="container">
    <h1 class="titulo">Agregar Usuario</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form class="formulario" method="POST">
      <label>Usuario:</label>
      <input type="text" name="usuario" required>

      <label>Contraseña:</label>
      <input type="password" name="contrasena" required>

      <label>Rol:</label>
      <select name="rol" required>
        <option value="admin">Admin</option>
        <option value="entrenador">Entrenador</option>
        <option value="miembro" selected>Miembro</option>
        <option value="invitado">Invitado</option>
      </select>

      <label>Nombre completo:</label>
      <input type="text" name="nombre">

      <label>Email:</label>
      <input type="email" name="email">

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar</button>
        <a href="usuarios.php" class="btn outline">Volver</a>
      </div>
    </form>
  </div>
</body>
</html>
