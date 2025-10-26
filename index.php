<?php $include_css = true; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <!--Inf meta-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="keywords" content="html, css, js, php">

  <!-- Título -->
  <title>GYMember | Gimnasio</title>

  <!-- Ícono -->
  <link rel="icon" href="assets/img/icono.ico">    
  
  <!-- CSS & Otros --> 
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
  <!-- Header -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <!-- Hero -->
  <main id="inicio" class="hero">
    <div class="hero-grid">
      <div class="hero-text container">
        <span class="kicker">Fitness</span>
        <h1>Ayuda para tu fitness corporal ideal</h1>
        <p>Todo lo que necesitás para acceder a un mundo de deporte, salud y bienestar.</p>
        <div class="btn_hero">
          <a class="btn outline" href="#contacto">Unite a nosotros</a>
          <a class="btn_simple" href="#planes">Ver planes</a>
        </div>
      </div>
      <div class="hero-image-outside">
        <img src="assets/img/banner.webp" alt="Imagen hero fitness" />
      </div>
    </div>
  </main>

  <!-- Sobre nosotros -->
  <?php include 'php/includes/nosotros.php'; ?>

  <!-- Sobre clases -->
  <?php include 'php/includes/clases.php'; ?>

  <!-- Planes -->
  <?php include 'php/includes/planes.php'; ?>

  <!-- Contacto -->
  <?php include 'php/includes/contacto.php'; ?>

  <!-- Tienda / Productos -->
  <?php include 'php/includes/scroll_tienda.php'; ?>

  <!-- Footer -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>

</html>
