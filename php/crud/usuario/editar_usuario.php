<!-- USUARIO | EDITAR USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: usuarios.php");
    exit;
}

$mensaje = "";

// Actualizar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $rol = $_POST['rol'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);

    // Foto (si se cambia)
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $nombre_foto = uniqid() . "_" . basename($_FILES['foto']['name']);
        $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/gymember/uploads/' . $nombre_foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
        $foto = $nombre_foto;
    }

    // Actualizar usuario
    if ($foto) {
        $query = $conexion->prepare("UPDATE usuarios SET usuario=?, rol=?, nombre=?, apellido=?, email=?, telefono=?, foto=? WHERE id=?");
        $query->bind_param("sssssssi", $usuario, $rol, $nombre, $apellido, $email, $telefono, $foto, $id);
    } else {
        $query = $conexion->prepare("UPDATE usuarios SET usuario=?, rol=?, nombre=?, apellido=?, email=?, telefono=? WHERE id=?");
        $query->bind_param("ssssssi", $usuario, $rol, $nombre, $apellido, $email, $telefono, $id);
    }

    if ($query->execute()) {
        // Si cambió a miembro, crear registro si no existe
        if ($rol === 'miembro') {
            $check = $conexion->prepare("SELECT id FROM miembros WHERE usuario_id=?");
            $check->bind_param("i", $id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $insert = $conexion->prepare("INSERT INTO miembros (usuario_id, estado) VALUES (?, 'inactivo')");
                $insert->bind_param("i", $id);
                $insert->execute();
            }
        } else {
            // Si ya no es miembro, elimina su registro en miembros
            $del = $conexion->prepare("DELETE FROM miembros WHERE usuario_id=?");
            $del->bind_param("i", $id);
            $del->execute();
        }

        header("Location: usuarios.php");
        exit;
    } else {
        $mensaje = "Error al actualizar usuario.";
    }
}

// Traer datos
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

  <title>GYMember | Editar Usuario</title>

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
    <h1 class="titulo">Editar Usuario</h1>
    <?php if ($mensaje) echo "<p style='color:red;'>$mensaje</p>"; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
      <label>Usuario:</label>
      <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>

      <label>Rol:</label>
      <select name="rol" required>
        <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="entrenador" <?= $usuario['rol'] == 'entrenador' ? 'selected' : '' ?>>Entrenador</option>
        <option value="miembro" <?= $usuario['rol'] == 'miembro' ? 'selected' : '' ?>>Miembro</option>
        <option value="invitado" <?= $usuario['rol'] == 'invitado' ? 'selected' : '' ?>>Invitado</option>
      </select>

      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">

      <label>Apellido:</label>
      <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>">

      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

      <label>Teléfono:</label>
      <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

      <label>Foto:</label>
      <?php if ($usuario['foto']): ?>
        <img src="/gymember/uploads/<?= htmlspecialchars($usuario['foto']) ?>" width="80" style="border-radius:8px;margin-bottom:10px;">
      <?php endif; ?>
      <input type="file" name="foto" accept="image/*">

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar Cambios</button>
        <a href="usuarios.php" class="btn outline">Cancelar</a>
      </div>
    </form>
  </div>

  <!-- FOOTER -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>

</body>
</html>
