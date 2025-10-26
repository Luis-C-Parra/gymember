<!-- PLANES -->
<?php
  if (isset($include_css) && $include_css) {
    echo '<link rel="stylesheet" href="/gymember/assets/css/planes.css">';
  }
?>


<section class="planes">
  <p class="kicker">Planes</p>
  <h2>Elegí tu Plan</h2>
  
  <!-- Toggle con Botones -->
  <div class="toggle">
    <button id="btnMensual" class="toggle-btn activo" aria-pressed="true">Mensual</button>
    <button id="btnAnual" class="toggle-btn" aria-pressed="false">Anual</button>
  </div>
  <p class="toggle-ahorro">Ahorra 20% con planes anuales</p>

  <!-- Planes Mensuales (activo por defecto) -->
  <div class="grupo-planes activo" id="planesMensual">
    <div class="cards-container">
      <!-- Plan Básico Mensual -->
      <div class="card secundon" data-plan="basico" data-periodo="mensual">
        <h3 class="titulo">Plan Básico</h3>
        <p class="precio">$25.000</p>
        <p class="frecuencia">Mensuales</p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 2 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> 1 suplemento gratuito</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> 3 días por semana</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Plan Full Mensual -->
      <div class="card secundon" data-plan="full" data-periodo="mensual">
        <div class="etiqueta">Oferta especial</div>
        <h3 class="titulo">Plan Full</h3>
        <p class="precio">$39.000</p>
        <p class="frecuencia">Mensuales</p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 4 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 3 suplementos gratuitos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 5 días por semana</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Plan Premium Mensual -->
      <div class="card secundon" data-plan="premium" data-periodo="mensual">
        <h3 class="titulo">Plan Premium</h3>
        <p class="precio">$60.000</p>
        <p class="frecuencia">Mensuales</p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 7 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 5 suplementos gratuitos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Tarjeta de acceso al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Paquete Atleta Mensual -->
      <div class="card secundon" data-plan="atleta" data-periodo="mensual">
        <h3 class="titulo">Paquete Atleta</h3>
        <p class="precio">$105.000</p>
        <p class="frecuencia">Mensuales</p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Indumentaria gratuita</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Todos los programas de entrenamiento incluidos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Asesoría fitness gratuita</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Suplemento gratuito</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Tarjeta de acceso al gimnasio</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>
    </div>
  </div>

  <!-- Planes Anuales (OCULTO inicialmente - NO tiene 'activo') -->
  <div class="grupo-planes" id="planesAnual">  <!-- Nota: Solo 'grupo-planes', sin 'activo' -->
    <div class="cards-container">
      <!-- Plan Básico Anual -->
      <div class="card secundon" data-plan="basico" data-periodo="anual">
        <h3 class="titulo">Plan Básico</h3>
        <p class="precio">$240.000</p>
        <p class="frecuencia">Anuales <span class="ahorro">(Ahorra 20%)</span></p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 2 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> 1 suplemento gratuito</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> 3 días por semana</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Plan Full Anual -->
      <div class="card secundon" data-plan="full" data-periodo="anual">
        <div class="etiqueta">Oferta especial</div>
        <h3 class="titulo">Plan Full</h3>
        <p class="precio">$375.000</p>
        <p class="frecuencia">Anuales <span class="ahorro">(Ahorra 20%)</span></p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 4 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 3 suplementos gratuitos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 5 días por semana</li>
          <li><iconify-icon icon="mdi:close-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Plan Premium Anual -->
      <div class="card secundon" data-plan="premium" data-periodo="anual">
        <h3 class="titulo">Plan Premium</h3>
        <p class="precio">$576.000</p>
        <p class="frecuencia">Anuales <span class="ahorro">(Ahorra 20%)</span></p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 7 consultas con entrenador fitness</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Seguimiento nutricional</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> 5 suplementos gratuitos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Tarjeta de acceso al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Entrenador personal</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>

      <!-- Paquete Atleta Anual -->
      <div class="card secundon" data-plan="atleta" data-periodo="anual">
        <h3 class="titulo">Paquete Atleta</h3>
        <p class="precio">$1.000.000</p>
        <p class="frecuencia">Anuales <span class="ahorro">(Ahorra 20%)</span></p>
        <ul class="beneficios">
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Acceso ilimitado al gimnasio</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Indumentaria gratuita</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Todos los programas de entrenamiento incluidos</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Asesoría fitness gratuita</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Suplemento gratuito</li>
          <li><iconify-icon icon="mdi:check-circle"></iconify-icon> Tarjeta de acceso al gimnasio</li>
        </ul>
        <button class="btn-inscribir">Inscríbete ahora</button>
      </div>
    </div>
  </div>
</section>

<script>
  // JS con logs para debug (ver en Console F12)
  document.addEventListener('DOMContentLoaded', function() {
    console.log('JS cargado: Iniciando toggle...'); // Log para verificar

    const btnMensual = document.getElementById('btnMensual');
    const btnAnual = document.getElementById('btnAnual');
    const planesMensual = document.getElementById('planesMensual');
    const planesAnual = document.getElementById('planesAnual');
    const btnsInscribir = document.querySelectorAll('.btn-inscribir');

    if (!btnMensual || !planesMensual) {
      console.error('Error: Elementos no encontrados (IDs mal?');
      return;
    }

    // Función para togglear planes
    function togglePlanes(isAnual) {
      console.log('Toggle llamado:', isAnual ? 'Anual' : 'Mensual'); // Log para debug

      if (isAnual) {
        btnMensual.classList.remove('activo');
        btnMensual.setAttribute('aria-pressed', 'false');
        btnAnual.classList.add('activo');
        btnAnual.setAttribute('aria-pressed', 'true');
        planesMensual.classList.remove('activo');
        planesAnual.classList.add('activo');
      } else {
        btnAnual.classList.remove('activo');
        btnAnual.setAttribute('aria-pressed', 'false');
        btnMensual.classList.add('activo');
        btnMensual.setAttribute('aria-pressed', 'true');
        planesAnual.classList.remove('activo');
        planesMensual.classList.add('activo');
      }
    }

    // Event listeners para toggle
    btnMensual.addEventListener('click', () => togglePlanes(false));
    btnAnual.addEventListener('click', () => togglePlanes(true));

    // Inicial: Mensual activo
    togglePlanes(false);
    console.log('Inicialización completa: Mensual activo'); // Log final

    // Event listeners para botones de inscripción (simulación)
    btnsInscribir.forEach(btn => {
      btn.addEventListener('click', function() {
        const card = this.closest('.card');
        const plan = card.dataset.plan;
        const periodo = card.dataset.periodo;
        const precio = card.querySelector('.precio').textContent;
        alert(`¡Genial! Te inscribirás al Plan ${plan.charAt(0).toUpperCase() + plan.slice(1)} (${periodo}) por ${precio}.\n\nRedirigiendo al formulario de pago...`);
        console.log(`Inscripción: ${plan} - ${periodo}`);
      });
    });
  });
</script>