<!-- REGISTER -->
<?php $include_css = true; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear cuenta | GYMember</title>

  <!-- Ícono y fuentes -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/login.css">
</head>

<body>
  <?php include(__DIR__ . '/../includes/navbar.php'); ?>

  <main class="auth-section">
    <div class="auth-container">
      <h1>Crear cuenta</h1>

      <form action="../acciones/registrar_usuario.php" method="POST">
        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required>
        </div>

        <div class="form-group">
          <label for="email">Correo electrónico</label>
          <input type="email" id="email" name="email" placeholder="tuemail@ejemplo.com" required>
        </div>

        <div class="form-group">
          <label for="usuario">Nombre de usuario</label>
          <input type="text" id="usuario" name="usuario" placeholder="tuusuario" required>
        </div>

        <div class="form-group">
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" placeholder="********" required>
        </div>

        <div class="form-group">
          <label for="confirmar">Confirmar contraseña</label>
          <input type="password" id="confirmar" name="confirmar" placeholder="********" required>
        </div>

        <button class="btn solid" type="submit">Registrarse</button>

        <div class="form-note">
          ¿Ya tenés cuenta? <a href="login.php">Iniciar sesión</a>
        </div>
      </form>
    </div>
  </main>

  <?php include(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
