/* Maneja el menú del usuario y el logout. Adaptado a #menuUsuario */
function manejarUsuario(accion) {
  const menu = document.getElementById('menuUsuario');
  if (!menu) return false;

  if (accion === 'toggle') {
    // alterna la clase hidden (que ya tenías)
    const oculto = menu.classList.contains('hidden');
    if (oculto) {
      menu.classList.remove('hidden');
      menu.setAttribute('aria-hidden', 'false');
    } else {
      menu.classList.add('hidden');
      menu.setAttribute('aria-hidden', 'true');
    }
    return false;
  }

  if (accion === 'logout') {
    if (confirm('¿Deseás cerrar sesión?')) {
      window.location.href = '/gymember/php/acciones/logout.php';
    }
    return false;
  }
}

// Cerrar el menú si se hace clic fuera
document.addEventListener('click', function(e) {
  const menu = document.getElementById('menuUsuario');
  const toggleBtn = document.querySelector('.user-toggle');
  if (!menu) return;
  if (!e.target.closest('.user-dropdown')) {
    if (!menu.classList.contains('hidden')) {
      menu.classList.add('hidden');
      menu.setAttribute('aria-hidden', 'true');
    }
  }
});
