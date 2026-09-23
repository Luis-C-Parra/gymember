<?php
session_start();

// -------------------------------------------
// 1) COMPROBAR SI HAY USUARIO LOGUEADO
// -------------------------------------------
$usuarioLogueado = isset($_SESSION['usuario_id']);

$usuario = [];
if ($usuarioLogueado) {
    $usuario = [
        'id'      => $_SESSION['usuario_id'],
        'nombre'  => $_SESSION['nombre'] ?? '',
        'email'   => $_SESSION['email'] ?? '',
        'usuario' => $_SESSION['usuario'] ?? '',
        'rol'     => $_SESSION['rol'] ?? 'usuario'
    ];
}

// -------------------------------------------
// 2) OBTENER LOS DATOS DEL PLAN DESDE POST
// -------------------------------------------
$planID   = $_POST['plan_id'] ?? 0;
$plan     = $_POST['plan'] ?? '';
$precio   = $_POST['precio'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Completar Membresía</title>
<link rel="stylesheet" href="../../assets/css/selec_plan.css">
<link rel="stylesheet" href="../../assets/css/style.css">

<style>
  .resumen-membresia {
      background: #111;
      color: #fff;
      padding: 1.2rem;
      border-radius: 10px;
      margin-bottom: 1.5rem;
      font-size: 1.1rem;
  }
</style>

</head>
<body>

<div class="contenedor-form">
  <main class="contenedor">
    <h1>Completa tu Membresía</h1>
    <p>Ingresá tus datos para finalizar la suscripción.</p>

    <!-- RESUMEN -->
    <div class="resumen-membresia">
      <h2>Resumen de tu Membresía</h2>
      <p><strong>Plan seleccionado:</strong> <?= htmlspecialchars($plan) ?></p>
      <p><strong>Precio:</strong> $<?= number_format($precio, 0, ',', '.') ?></p>
    </div>

    <!-- FORMULARIO PRINCIPAL -->
    <form action="../acciones/pagos_procesar_membresia.php" method="POST" class="formulario">

      <!-- Datos ocultos del plan -->
      <input type="hidden" name="plan_id" value="<?= $planID ?>">
      <input type="hidden" name="monto" value="<?= $precio ?>">

      <?php if (!$usuarioLogueado): ?>
        <!-- SI NO ESTÁ LOGUEADO → FORMULARIO DE REGISTRO -->
        <h2>Datos del Usuario</h2>

        <label>Nombre completo:</label>
        <input type="text" name="nombre" required>

        <label>Correo electrónico:</label>
        <input type="email" name="email" required>

        <label>Nombre de usuario:</label>
        <input type="text" name="usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <label>Confirmar contraseña:</label>
        <input type="password" name="password_confirm" required>

        <input type="hidden" name="registro_nuevo" value="1">

      <?php else: ?>
        <!-- SI ESTÁ LOGUEADO → NO PEDIR REGISTRO -->
        <h2>Usuario actual</h2>
        <p><strong><?= htmlspecialchars($usuario['nombre']) ?></strong> (<?= htmlspecialchars($usuario['email']) ?>)</p>

        <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
      <?php endif; ?>

      <h2>Fecha de inicio</h2>
      <input type="date" name="fecha_inicio" value="<?= date('Y-m-d') ?>" required>

      <h2>Método de Pago</h2>
      <select name="metodo_pago" required>
        <option value="">-- Seleccioná un método --</option>
        <option value="efectivo">Efectivo</option>
        <option value="transferencia">Transferencia</option>
        <option value="tarjeta">Tarjeta</option>
      </select>

      <h2>Comprobante</h2>
      <p>Al confirmar el pago se generará automáticamente tu comprobante en PDF.</p>

      <button type="submit" class="btn-enviar">Confirmar Membresía</button>

    </form>
  </main>
</div>

<script src="../../assets/js/selec_plan.js"></script>

</body>
</html>
