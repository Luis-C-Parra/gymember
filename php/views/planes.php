<?php
// Config
$include_css = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GYMember | Planes de Membresía</title>

  <!-- Ícono -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">

  <!-- Estilos generales -->
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">

  <!-- CSS de la sección de planes (se carga dentro del include también si $include_css está definido) -->
  <link rel="stylesheet" href="/gymember/assets/css/planes.css">

  <!-- Tipografías -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Iconify -->
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>

<body>

  <!-- Navbar -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <header class="planes-header">
      <h1 class="titulo">Planes de Membresía</h1>
      <p class="subtitulo">Descubrí el plan que se adapta a tu entrenamiento y objetivos 💪</p>
    </header>

    <!-- Bloque de planes -->
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/planes.php'); ?>
  </main>

  <!-- Footer -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>

</body>
</html>

