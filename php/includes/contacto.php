<!-- CONTACTO -->
<?php
if (isset($include_css) && $include_css) {
  echo '<link rel="stylesheet" href="/gymember/assets/css/contacto.css">';
}
?>

<section id="contacto" class="contact-section">
  <div class="contact">
    <h2>Contactanos</h2>
    <p>Envíanos tu email para recibir actualizaciones y más información sobre nuestro gimnasio.</p>

    <form class="email-form" action="/gymember/php/acciones/enviar_contacto.php" method="POST">
        <input type="email" name="email" placeholder="Ingresá tu correo electrónico" required />
        <button type="submit" class="btn solid">Suscribirme</button>
    </form>

    <?php if (isset($_GET['ok'])): ?>
      <p class="success-msg">✅ ¡Gracias por suscribirte! Pronto recibirás nuestras novedades.</p>
    <?php elseif (isset($_GET['error'])): ?>
      <p class="error-msg">❌ Hubo un problema al enviar el correo. Intentalo más tarde.</p>
    <?php endif; ?>
  </div>
</section>