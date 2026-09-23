<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/rutas.php';

$logueado = isset($_SESSION['usuario']);
$rol = $_SESSION['rol'] ?? '';
$nombre = $_SESSION['nombre'] ?? '';
$mostrarNombre = $nombre ? explode(' ', trim($nombre))[0] : ($_SESSION['usuario'] ?? '');
?>

<header class="site-header">
  <div class="container header-inner">
    <div class="logo">
      <a href="/gymember/index.php#inicio">
        <img src="/gymember/assets/img/logotipo.png" alt="GYMember logo">
      </a>
      <h1 class="logo_gym"><span style="color:white">GYM</span>ember</h1>
    </div>

    <!-- Botón hamburguesa -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú" autocomplete="off">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <!-- Navegación -->
    <nav class="main-nav" aria-label="Navegación principal">
      <ul id="nav-links" class="nav-links">
        <li><a href="/gymember/index.php#inicio">Inicio</a></li>
        <li><a href="/gymember/index.php#nosotros">Nosotros</a></li>
        <li><a href="/gymember/index.php#clases">Clases</a></li>
        <li><a href="/gymember/php/views/planes.php">Planes</a></li>
        <li><a href="/gymember/php/views/tienda.php">Tienda</a></li>
        <li><a href="/gymember/index.php#contacto">Contacto</a></li>

        <?php if (!$logueado): ?>
          <li><a href="/gymember/php/views/login.php" class="btn solid">Login</a></li>
        <?php else: ?>
          <li class="user-dropdown">
            <button class="user-toggle" autocomplete="off">
              <iconify-icon icon="mdi:account-circle" class="icon-user"></iconify-icon>
              <span class="user-name"><?= htmlspecialchars($mostrarNombre) ?></span>
              <iconify-icon icon="mdi:chevron-down" class="chev" id="flecha"></iconify-icon>
            </button>

            <div id="menuUsuario" class="dropdown hidden">
              <ul>
                <li><a href="/gymember/php/views/perfil.php">Mi perfil</a></li>
                <?php if ($rol === 'admin'): ?>
                  <li class="divider"></li>
                  <li class="dropdown-title">Administración</li>
                  <li><a href="/gymember/php/crud/usuario/usuarios.php">Gestión de usuarios</a></li>
                  <li><a href="/gymember/php/crud/miembros/miembros.php">Gestión de miembros</a></li>
                  <li><a href="/gymember/php/crud/pagos/admin_pagos.php">Gestión de pagos</a></li>
                  <li><a href="/gymember/php/crud/planes/planes.php">Gestión de planes</a></li>
                  <li><a href="/gymember/php/crud/tienda/admin_productos.php">Gestión de tienda</a></li>
                <?php endif; ?>
                <li class="divider"></li>
                <li><a href="/gymember/php/acciones/logout.php" class="logout">Cerrar sesión</a></li>
              </ul>
            </div>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>

<script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
<script>
  // Menú usuario
  const userToggle = document.querySelector(".user-toggle");
  const menuUsuario = document.getElementById("menuUsuario");
  const flecha = document.getElementById("flecha");

  userToggle?.addEventListener("click", (e) => {
    e.stopPropagation();
    menuUsuario.classList.toggle("show");
    flecha.style.transform = menuUsuario.classList.contains("show") ? "rotate(180deg)" : "rotate(0deg)";
  });

  document.addEventListener("click", () => {
    menuUsuario?.classList.remove("show");
    if (flecha) flecha.style.transform = "rotate(0deg)";
  });

  // Menú hamburguesa
  const menuToggle = document.getElementById("menu-toggle");
  const navLinks = document.getElementById("nav-links");

  menuToggle.addEventListener("click", (e) => {
    e.stopPropagation();
    menuToggle.classList.toggle("open"); // animación de X
    navLinks.classList.toggle("open");   // mostrar/ocultar menú
  });

  document.addEventListener("click", (e) => {
    if (!navLinks.contains(e.target) && !menuToggle.contains(e.target)) {
      navLinks.classList.remove("open");
      menuToggle.classList.remove("open");
    }
  });
</script>
