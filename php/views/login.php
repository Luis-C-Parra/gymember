<!-- LOGIN -->
<?php $include_css = true; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión | GYMember</title>

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
      <h1>Iniciar sesión</h1>

      <form action="../acciones/login_usuario.php" method="POST">
        <div class="form-group">
          <label for="usuario">Usuario o correo electrónico</label>
          <input type="text" id="usuario" name="usuario" placeholder="tuusuario" required />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="********" required />
        </div>

        <button class="btn solid" type="submit">Ingresar</button>

        <div class="form-note">
          ¿No tenés cuenta? <a href="register.php">Crear una cuenta</a>
        </div>
      </form>
    </div>
  </main>

  <?php include(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
