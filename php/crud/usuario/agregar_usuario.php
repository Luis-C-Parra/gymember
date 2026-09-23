<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$mensaje = "";

// Guardar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario   = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    $rol       = $_POST['rol'];
    $nombre    = trim($_POST['nombre']);
    $apellido  = trim($_POST['apellido']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    
    // Validación de contraseña
    if (strlen($contrasena) < 8) {
        $mensaje = "❌ La contraseña debe tener al menos 8 caracteres.";
    } else {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Manejo simple de foto
        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $nombre_foto = uniqid() . "_" . basename($_FILES['foto']['name']);
            $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/gymember/uploads/' . $nombre_foto;
            move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
            $foto = $nombre_foto;
        }

        // Insertar usuario
        $sql = "INSERT INTO usuarios (usuario, contrasena, rol, nombre, apellido, email, telefono, foto)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $conexion->prepare($sql);
        $query->bind_param("ssssssss", $usuario, $contrasena_hash, $rol, $nombre, $apellido, $email, $telefono, $foto);

        if ($query->execute()) {
            $nuevo_id = $query->insert_id;

            // Si es miembro, también se crea en la tabla miembros
            if ($rol === 'miembro') {
                $insert_miembro = $conexion->prepare("INSERT INTO miembros (usuario_id, estado) VALUES (?, 'inactivo')");
                $insert_miembro->bind_param("i", $nuevo_id);
                $insert_miembro->execute();
            }

            header("Location: usuarios.php");
            exit;
        } else {
            $mensaje = "❌ Error al agregar usuario: " . $conexion->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Agregar Usuario</title>

  <link rel="icon" href="/gymember/assets/img/icono.ico">    
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <div class="container">
    <h1 class="titulo">Agregar Usuario</h1>
    <?php if ($mensaje) echo "<p style='color:red;'>$mensaje</p>"; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
      <label>Usuario:</label>
      <input type="text" name="usuario" required>

      <label>Contraseña:</label>
      <input type="password" name="contrasena" minlength="8" required placeholder="Mínimo 8 caracteres">

      <label>Rol:</label>
      <select name="rol" required>
        <option value="">Seleccionar...</option>
        <option value="invitado">Invitado</option>
        <option value="miembro">Miembro</option>
        <option value="entrenador">Entrenador</option>
        <option value="admin">Admin</option>
      </select>

      <label>Nombre:</label>
      <input type="text" name="nombre">

      <label>Apellido:</label>
      <input type="text" name="apellido">

      <label>Email:</label>
      <input type="email" name="email">

      <label>Teléfono:</label>
      <input type="text" name="telefono">

      <label>Foto (opcional):</label>
      <input type="file" name="foto" accept="image/*">

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar</button>
        <a href="usuarios.php" class="btn outline">Volver</a>
      </div>
    </form>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
