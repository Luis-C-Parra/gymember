<!-- NOSOTROS -->
<?php
  if (isset($include_css) && $include_css) {
    echo '<link rel="stylesheet" href="/gymember/assets/css/nosotros.css">';
  }
?>

<section id="nosotros" class="section about-section">
  <div class="container about-grid">
    <div class="about-text">
      <h2>Sobre nosotros</h2>
      <p>
        En <strong>GYMember</strong> no importa si ya tenés experiencia o si recién estás comenzando a practicar actividad física. 
        Nosotros estamos para ayudarte a iniciar en este mundo. 
        Contamos con instalaciones modernas, equipamientos de última generación y entrenadores especializados que te acompañarán en cada paso de tu transformación.
      </p>
      <ul>
        <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Instalaciones modernas</li>
        <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Entrenadores especializados</li>
        <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> Rutinas personalizadas</li>
        <li><iconify-icon icon="mdi:check-circle" class="icon"></iconify-icon> La mejor atención</li>
      </ul>
    </div>
    <figure class="about-image">
      <img src="assets/img/nosotros_2.jpg" alt="Instalaciones de gimnasio GYMember">
    </figure>
  </div>

  <div class="container about-cards">
    <article class="about-card">
      <iconify-icon icon="mdi:arm-flex" class="icon pink"></iconify-icon>
      <h3>Resultados Reales</h3>
      <p>Lográ cambios visibles y medibles con entrenamientos efectivos.</p>
    </article>

    <article class="about-card">
      <iconify-icon icon="mdi:weight-lifter" class="icon yellow"></iconify-icon>
      <h3>Equipamiento Moderno</h3>
      <p>Entrená con máquinas y herramientas de última generación para un rendimiento óptimo.</p>
    </article>

    <article class="about-card">
      <iconify-icon icon="mdi:emoticon-happy" class="icon pink"></iconify-icon>
      <h3>Ambiente Motivador</h3>
      <p>Entrená rodeado de energía positiva que te impulsa a dar siempre lo mejor.</p>
    </article>
  </div>
</section>
