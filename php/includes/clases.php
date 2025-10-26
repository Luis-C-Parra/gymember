<!-- CLASES -->
<?php
  if (isset($include_css) && $include_css) {
    echo '<link rel="stylesheet" href="/gymember/assets/css/clases.css">';
  }
?>

<section id="clases" class="section-clases">
  <div class="container">

    <!-- Bloque 1 -->
    <section class="bloque">
      <div class="texto">
        <h2>Clases grupales</h2>
        <p>
          En nuestras clases grupales encontrarás la combinación perfecta de energía,
          motivación y diversión. Diseñadas para todos los niveles, estas sesiones te permiten
          entrenar en compañía, con la guía de instructores profesionales que te impulsarán
          a dar lo mejor de vos. Cada clase es una oportunidad para superarte,
          compartir con otros y disfrutar del entrenamiento en un ambiente dinámico
          y lleno de buena vibra.
        </p>
        <a href="#" class="btn-vermas">Ver más <iconify-icon icon="mdi:arrow-right"></iconify-icon></a>
      </div>
      <div class="imagen">
        <img src="assets/img/clases_grupales.png" alt="Clases grupales">
      </div>
    </section>

    <!-- Bloque 2 -->
    <section class="bloque">
      <div class="imagen">
        <img src="assets/img/entrenamiento_funcional.png" alt="Entrenamiento funcional">
      </div>
      <div class="texto">
        <h2>Entrenamiento funcional</h2>
        <ul>
          <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Circuitos dinámicos</li>
          <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Mejora la postura y estabilidad</li>
          <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Quema la grasa corporal</li>
          <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Clases diarias</li>
        </ul>
        <a href="#" class="btn-vermas">Ver más <iconify-icon icon="mdi:arrow-right"></iconify-icon></a>
      </div>
    </section>

  </div>
</section>
