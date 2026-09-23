<?php
// Incluye conexión
require_once __DIR__ . '/../../config/conexion.php';

// Cargar CSS
if (isset($include_css) && $include_css) {
  echo '<link rel="stylesheet" href="/gymember/assets/css/planes.css">';
}

// Renderizar beneficios
function render_beneficios($plan) {
  echo '<ul class="beneficios">';
  for ($i = 1; $i <= 7; $i++) {
    $texto = trim($plan["item{$i}_texto"] ?? '');
    if ($texto === '') continue;
    $icon = (isset($plan["item{$i}_icono"]) && $plan["item{$i}_icono"] === 'x')
      ? 'mdi:close-circle'
      : 'mdi:check-circle';
    echo "<li><iconify-icon icon=\"$icon\"></iconify-icon> " . htmlspecialchars($texto) . "</li>";
  }
  echo '</ul>';
}

// Traer planes activos 
$planes = ['mensual' => [], 'anual' => []];
$res = $conn->query("
  SELECT * 
  FROM planes 
  WHERE activo = 1 AND periodo IN ('mensual','anual')
  ORDER BY CASE TRIM(periodo)
    WHEN 'mensual' THEN 1
    WHEN 'anual' THEN 2
    ELSE 3 END,
  precio ASC
");

if ($res) {
  while ($row = $res->fetch_assoc()) {
    $periodo = trim($row['periodo']) === 'anual' ? 'anual' : 'mensual';
    $planes[$periodo][] = $row;
  }
}
?>

<section class="planes">
  <p class="kicker">Planes</p>
  <h2>Elegí tu Plan</h2>

  <!-- Toggle -->
  <div class="toggle" role="tablist" aria-label="Seleccionar periodo">
    <button id="btnMensual" class="toggle-btn activo" aria-pressed="true" aria-controls="planesMensual">Mensual</button>
    <button id="btnAnual" class="toggle-btn" aria-pressed="false" aria-controls="planesAnual">Anual</button>
  </div>
  <p class="toggle-ahorro">Ahorra 20% con planes anuales</p>

  <!-- GRUPO: MENSUAL (activo por defecto) -->
  <div class="grupo-planes activo" id="planesMensual" aria-live="polite">
    <div class="cards-container">
      <?php if (!empty($planes['mensual'])): ?>
        <?php foreach ($planes['mensual'] as $plan): ?>
          <div class="card secundon" data-plan="<?= htmlspecialchars($plan['nombre']) ?>" data-periodo="<?= htmlspecialchars($plan['periodo']) ?>">
            <?php if (stripos($plan['nombre'],'full') !== false): ?>
              <div class="etiqueta">Oferta especial</div>
            <?php endif; ?>
            
            <h3 class="titulo"><?= htmlspecialchars($plan['nombre']) ?></h3>
            <p class="precio">$<?= number_format($plan['precio'], 0, ',', '.') ?></p>
            <p class="frecuencia"><?= ucfirst($plan['periodo']) ?>es</p>

            <?php render_beneficios($plan); ?>

            <form action="/gymember/php/views/selec_plan.php" method="POST">
              <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
              <input type="hidden" name="plan" value="<?= htmlspecialchars($plan['nombre']) ?>">
              <input type="hidden" name="periodo" value="<?= htmlspecialchars($plan['periodo']) ?>">
              <input type="hidden" name="precio" value="<?= htmlspecialchars($plan['precio']) ?>">
              <button type="submit" class="btn-inscribir">Inscribite ahora</button>
            </form> 
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="sin-planes">No hay planes disponibles actualmente.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- GRUPO: ANUAL -->
  <div class="grupo-planes" id="planesAnual" aria-live="polite">
    <div class="cards-container">
      <?php if (!empty($planes['anual'])): ?>
        <?php foreach ($planes['anual'] as $plan): ?>
          <div class="card secundon" data-plan="<?= htmlspecialchars($plan['nombre']) ?>" data-periodo="<?= htmlspecialchars($plan['periodo']) ?>">
            <?php if (stripos($plan['nombre'],'full') !== false): ?>
              <div class="etiqueta">Oferta especial</div>
            <?php endif; ?>

            <h3 class="titulo"><?= htmlspecialchars($plan['nombre']) ?></h3>
            <p class="precio">$<?= number_format($plan['precio'], 0, ',', '.') ?></p>
            <p class="frecuencia"><?= ucfirst($plan['periodo']) ?>es <span class="ahorro">(Ahorra 20%)</span></p>

            <?php render_beneficios($plan); ?>

            <form action="/gymember/php/views/selec_plan.php" method="POST">
              <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
              <input type="hidden" name="plan" value="<?= htmlspecialchars($plan['nombre']) ?>">
              <input type="hidden" name="periodo" value="<?= htmlspecialchars($plan['periodo']) ?>">
              <input type="hidden" name="precio" value="<?= htmlspecialchars($plan['precio']) ?>">
              <button type="submit" class="btn-inscribir">Inscribite ahora</button>
            </form> 
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="sin-planes">No hay planes disponibles actualmente.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Toggle -->
<script>
  // JS
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

    // Mensual activo
    togglePlanes(false);
    console.log('Inicialización completa: Mensual activo'); // Log final

    // Event listeners para botones de inscripción -TODAVIA NO FUNCIONA-
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
