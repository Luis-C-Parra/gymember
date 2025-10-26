<!-- FOOTER | PIE DE PÁGINA -->
<?php
require_once __DIR__ . '/../../config/rutas.php';
if (isset($include_css) && $include_css) {
    echo '<link rel="stylesheet" href="' . RUTA_BASE . '/assets/css/footer.css">';
}
?>

<footer>
  <div class="footer-container">
    <div>
      <div class="footer-logo">
        <p class="logo">GYM<span style="color: var(--accent);">ember</span></p>
      </div>
      <p class="footer-text">
        Somos un centro deportivo con variedad de actividades.
      </p>
      <p class="footer-text">
        Visítanos en<br />
        Av. siempre viva 1234
      </p>
    </div>

    <div class="footer-links">
      <h4>GYMember</h4>
      <a href="#">Nosotros</a>
      <a href="#">Clases</a>
      <a href="#">Planes</a>
      <a href="#">Shop</a>
    </div>

    <div class="footer-links">
      <h4>Comp</h4>
      <a href="#">Términos & Condiciones</a>
      <a href="#">Política de Privacidad</a>
      <a href="#">FAQS</a>
      <a href="#">Soporte</a>
    </div>

    <div class="footer-links">
      <h4>Contacto</h4>
      <div class="contact-item">
        <iconify-icon icon="mdi:email-outline"></iconify-icon>
        <p>contacto@gymermber.com</p>
      </div>
      <div class="contact-item">
        <iconify-icon icon="mdi:phone-outline"></iconify-icon>
        <p>+54 11 0000-0000</p>
      </div>

      <div class="social-icons">
        <a href="#" aria-label="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
        <a href="#" aria-label="Twitter"><iconify-icon icon="mdi:twitter"></iconify-icon></a>
        <a href="#" aria-label="Facebook"><iconify-icon icon="mdi:facebook"></iconify-icon></a>
        <a href="#" aria-label="YouTube"><iconify-icon icon="mdi:youtube"></iconify-icon></a>
      </div>
    </div>
  </div>

  <!-- Nueva línea de copyright -->
  <div class="footer-bottom">
    <div class="copyright">
      <span>© 2025 IFTS4</span>
    </div>
    <div class="legal-links">
      <a href="#">Términos de uso</a>
      <a href="#">Política de privacidad</a>
    </div>
  </div>
</footer>

<!-- Scripts globales -->
<script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
<script src="/gymember/assets/js/main.js"></script>

