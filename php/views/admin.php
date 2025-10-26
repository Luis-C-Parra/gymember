<?php 
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

$include_css = true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración | GYMember</title>

  <link rel="icon" href="../../assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body>
  <?php include(__DIR__ . '/../includes/navbar.php'); ?>

  <main class="container">
    <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
    <p>Tu rol es: <strong><?php echo ucfirst($_SESSION['rol']); ?></strong></p>

    <div class="admin-cards">
      <a href="#" class="admin-card">Gestionar socios</a>
      <a href="#" class="admin-card">Ver pagos</a>
      <a href="#" class="admin-card">Usuarios del sistema</a>
    </div>
  </main>

  <?php include(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>
