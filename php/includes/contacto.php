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
    <form class="email-form">
        <input type="email" placeholder="Enter your email address" required />
        <button type="submit" class="btn solid">Subscribe</button>
    </form>
  </div>
</section>
