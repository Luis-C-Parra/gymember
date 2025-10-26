<!-- NAVBAR | BARRA DE NAVEGACIÓN -->
<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/rutas.php';

$logueado = isset($_SESSION['usuario']);
$rol = $_SESSION['rol'] ?? '';
$nombre = $_SESSION['nombre'] ?? '';
$mostrarNombre = $nombre ? explode(' ', trim($nombre))[0] : ($_SESSION['usuario'] ?? '');

if (isset($include_css) && $include_css) {
  echo '<link rel="stylesheet" href="' . RUTA_BASE . '/assets/css/navbar.css">';
}
?>

<header class="site-header">
  <div class="container header-inner">
    <div class="logo">
      <a href="/gymember/index.php#inicio">
        <img src="/gymember/assets/img/logotipo.png" alt="GYMember logo">
      </a>
      <h1 class="sr-only"><span style="color:white">GYM</span>ember</h1>
    </div>

    <nav class="main-nav" aria-label="Navegación principal">
      <ul>
        <li><a href="/gymember/index.php#inicio">Inicio</a></li>
        <li><a href="/gymember/index.php#nosotros">Nosotros</a></li>
        <li><a href="/gymember/index.php#clases">Clases</a></li>
        <li><a href="/gymember/index.php#planes">Planes</a></li>
        <li><a href="/gymember/php/views/tienda.php">Tienda</a></li>
        <li><a href="/gymember/index.php#contacto">Contacto</a></li>

        <?php if (!$logueado): ?>
          <li><a href="/gymember/php/views/login.php" class="btn solid">Login</a></li>
        <?php else: ?>
          <li class="user-dropdown">
            <button class="user-toggle" onclick="toggleMenu(event)">
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
                  <li><a href="/gymember/php/crud/planes/planes.php">Gestión de planes</a></li>
                  <li><a href="/gymember/php/crud/tienda/admin_productos.php">Gestión de tienda</a></li>
                  <li><a href="/gymember/php/crud/pagos/pagos.php">Gestión de pagos</a></li>
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

<!-- Menú desplegable -->
<script>
function toggleMenu(event) {
  event.stopPropagation();
  const menu = document.getElementById("menuUsuario");
  const flecha = document.getElementById("flecha");
  const isShown = menu.classList.contains("show");

  // Cambiar menú
  menu.classList.toggle("show", !isShown);
  flecha.style.transform = !isShown ? "rotate(180deg)" : "rotate(0deg)";
}

// Cerrar al hacer clic fuera
document.addEventListener("click", () => {
  document.getElementById("menuUsuario").classList.remove("show");
  document.getElementById("flecha").style.transform = "rotate(0deg)";
});
</script>
