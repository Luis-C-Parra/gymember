<?php
// views/editar_perfil.php
session_start();
require_once __DIR__ . '/../../config/conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? $_SESSION['id_usuario'];
$mensaje = "";

// Obtener datos (Añadimos 'email' a la consulta porque lo necesitamos para mandar el correo)
$query = $conn->prepare("SELECT usuario, nombre, apellido, telefono, foto, email FROM usuarios WHERE id=?");
$query->bind_param("i", $id);
$query->execute();
$usuarioData = $query->get_result()->fetch_assoc();
$query->close();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);

    // Foto
    $foto = $usuarioData['foto'];
    if (!empty($_FILES['foto']['name'])) {
        $nombre_foto = uniqid() . "_" . basename($_FILES['foto']['name']);
        $ruta_destino = $_SERVER['DOCUMENT_ROOT'] . '/gymember/assets/img/img_usuarios/' . $nombre_foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino);
        $foto = $nombre_foto;
    }

    // Actualizar DB
    $upd = $conn->prepare("UPDATE usuarios SET usuario=?, nombre=?, apellido=?, telefono=?, foto=? WHERE id=?");
    $upd->bind_param("sssssi", $usuario, $nombre, $apellido, $telefono, $foto, $id);
    
    if ($upd->execute()) {
        $mensaje = "Perfil actualizado correctamente.";
        
        // Refrescar datos en memoria local para la vista
        $usuarioData['usuario'] = $usuario;
        $usuarioData['nombre'] = $nombre;
        $usuarioData['apellido'] = $apellido;
        $usuarioData['telefono'] = $telefono;
        $usuarioData['foto'] = $foto;

        // ==============================================================
        // AUTOMATIZACIÓN DE SEGURIDAD: MANDAR MAIL AL PERFIL ACTUALIZADO
        // ==============================================================
        $email_destino = $usuarioData['email']; // Usamos el email original del usuario
        
        if (!empty($email_destino)) {
            $asunto = "🔒 GYMember - Alerta de Seguridad (Edición de Perfil)";
            
            // Cuerpo del correo maquetado en HTML puro
            $mensaje_correo = "
            <html>
            <head><title>Actualización de Perfil</title></head>
            <body style='font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px;'>
              <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e4e6eb;'>
                <h2 style='color: #1a202c;'>Aviso de Seguridad de Cuenta</h2>
                <p>Hola, <strong>" . htmlspecialchars($nombre . " " . $apellido) . "</strong>:</p>
                <p>Te informamos que los datos de tu cuenta en <strong>GYMember</strong> han sido modificados recientemente con éxito.</p>
                <div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #ff2e88; margin: 20px 0;'>
                    <strong>Acción registrada:</strong> Edición de datos personales.<br>
                    <strong>Fecha y hora del cambio:</strong> " . date('d/m/Y H:i:s') . "
                </div>
                <p style='font-size: 0.9rem; color: #666;'>Si tú realizaste este cambio, puedes ignorar este mensaje de seguridad. Si <strong>no</strong> realizaste esta acción, por favor contacta de inmediato con la administración del gimnasio para proteger tu acceso.</p>
              </div>
            </body>
            </html>
            ";

            // Cabeceras obligatorias para el despachador de correos de PHP (Envío HTML)
            $cabeceras = "MIME-Version: 1.0" . "\r\n";
            $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $cabeceras .= "From: no-reply@gymember.com" . "\r\n";

            // Despachar el correo en silencio (usamos '@' para que no rompa la pantalla si XAMPP falla al mandar mails por falta de SMTP)
            @mail($email_destino, $asunto, $mensaje_correo, $cabeceras);
        }

    } else {
        $mensaje = "Error al actualizar el perfil.";
    }
    $upd->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Editar Perfil | GYMember</title>

    <link rel="icon" href="/gymember/assets/img/icono.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/gymember/assets/css/crud.css">
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
    <link rel="stylesheet" href="/gymember/assets/css/footer.css">
    <link rel="stylesheet" href="/gymember/assets/css/style.css">
    <link rel="stylesheet" href="/gymember/assets/css/perfil.css">
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

    <main class="perfil">
        <div class="container">
        <h1 class="titulo">Editar Perfil</h1>
        
        <?php if ($mensaje): ?>
            <div class="<?= strpos($mensaje, 'Error') !== false ? 'alerta-error' : 'alerta-exito' ?>" style="margin-bottom: 20px; padding: 15px; border-radius: 5px; text-align: center; color: white; background-color: <?= strpos($mensaje, 'Error') !== false ? '#dc3545' : '#28a745' ?>;">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?= htmlspecialchars($usuarioData['usuario']) ?>" required>

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuarioData['nombre']) ?>">

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuarioData['apellido']) ?>">

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuarioData['telefono']) ?>">

            <label>Foto de perfil:</label>
            <?php if ($usuarioData['foto']): ?>
            <img src="/gymember/assets/img/img_usuarios/<?= htmlspecialchars($usuarioData['foto']) ?>" width="80" style="border-radius:8px;margin-bottom:10px;">
            <?php else: ?>
            <iconify-icon icon="mdi:account-circle-outline" width="80" height="80"></iconify-icon>
            <?php endif; ?>
            <input type="file" name="foto" accept="image/*">

            <div class="acciones">
            <button type="submit" class="btn solid">Guardar Cambios</button>
            <a href="../views/perfil.php" class="btn outline">Volver</a>
            </div>
        </form>
        </div>
    </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
</body>
</html>