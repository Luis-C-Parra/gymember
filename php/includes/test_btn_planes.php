<?php
// Este archivo simula los botones de los planes
// para probar la redirección al formulario selec_plan.php
  if (isset($include_css) && $include_css) {
    echo '<link rel="stylesheet" href="/gymember/assets/css/test_btn_planes.css">';
  }
// Podés incluirlo en tu index o en otra vista
// Ejemplo: include 'php/includes/planes_demo.php';
?>

<section class="contenedor" style="text-align:center; margin:50px auto;">
  <h1>Elegí tu plan</h1>
  <p>Simulación de botones de planes (versión de prueba)</p>

  <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-top:30px;">
    <a href="/gymember/php/views/selec_plan.php?plan=Básico" class="btn-plan">Plan Básico</a>
    <a href="/gymember/php/views/selec_plan.php?plan=Estándar" class="btn-plan">Plan Estándar</a>
    <a href="/gymember/php/views/selec_plan.php?plan=Premium" class="btn-plan">Plan Premium</a>
    <a href="/gymember/php/views/selec_plan.php?plan=Empresarial" class="btn-plan">Plan Empresarial</a>
  </div>
</section>