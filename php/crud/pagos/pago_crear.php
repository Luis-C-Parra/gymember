<?php
// pago_crear.php
include('../../../config/conexion.php'); 

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

session_start();

// Carga de planes y usuarios
$sql_planes = "SELECT id, nombre, precio, periodo FROM planes WHERE activo = 1 ORDER BY precio ASC";
$resultado_planes = $conn->query($sql_planes);
$planes = $resultado_planes ? $resultado_planes->fetch_all(MYSQLI_ASSOC) : [];

$sql_usuarios = "SELECT id, nombre, email FROM usuarios ORDER BY nombre ASC";
$resultado_usuarios = $conn->query($sql_usuarios);
$usuarios = $resultado_usuarios ? $resultado_usuarios->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();

// Variables para precarga de formulario (si hay error de validación)
$mensaje = $_SESSION['mensaje'] ?? '';
$datos_previos = $_SESSION['datos_formulario'] ?? [];
unset($_SESSION['mensaje']);
unset($_SESSION['datos_formulario']);

// Función auxiliar para mantener valores del formulario si hay errores
function get_valor($key, $default = '') {
    global $datos_previos;
    return htmlspecialchars($datos_previos[$key] ?? $default);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GyMember | Registrar pago</title>
    
    <link rel="icon" href="/gymember/assets/img/icono.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/gymember/assets/css/styles.css">
    <link rel="stylesheet" href="/gymember/assets/css/crud.css">
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
    <link rel="stylesheet" href="/gymember/assets/css/footer.css">
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

    <main class="container">
        <h1 class="titulo">Registrar Pago</h1>

        <?php if ($mensaje): ?>
            <div class="<?php echo strpos($mensaje, '❌') !== false ? 'alerta-error' : 'alerta-exito'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form action="pago_procesar.php" method="POST" class="formulario" id="form-pago">
            <input type="hidden" name="accion" value="crear">

            <label for="usuario_id">Usuario / Miembro:</label>
            <select id="usuario_id" name="usuario_id" required>
                <option value="">-- Seleccionar Miembro --</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?php echo $u['id']; ?>" <?php echo ($u['id'] == get_valor('usuario_id')) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($u['nombre'] . ' (' . $u['email'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="plan_id">Plan:</label>
            <select id="plan_id" name="plan_id" required onchange="actualizarMonto()">
                <option value="">-- Seleccionar plan --</option>
                <?php 
                $plan_sel = get_valor('plan_id');
                foreach ($planes as $plan): ?>
                    <option 
                        value="<?php echo $plan['id']; ?>" 
                        data-precio="<?php echo $plan['precio']; ?>" 
                        <?php echo ($plan_sel == $plan['id']) ? 'selected' : ''; ?>>
                        <?php echo $plan['nombre'] . " (" . ucfirst($plan['periodo']) . " - $" . number_format($plan['precio'], 0, ',', '.') . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="monto">Monto:</label>
            <input type="text" id="monto" name="monto" readonly value="<?php echo get_valor('monto'); ?>">

            <label for="metodo">Método de Pago:</label>
            <select id="metodo" name="metodo" required onchange="evaluarMetodoPago()">
                <option value="">-- Seleccionar método --</option>
                <option value="efectivo" <?php echo (get_valor('metodo') === 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                <option value="transferencia" <?php echo (get_valor('metodo') === 'transferencia') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                <option value="debito_mercadopago" <?php echo (get_valor('metodo') === 'debito_mercadopago') ? 'selected' : ''; ?>>Tarjeta Débito (Mercado Pago)</option>
            </select>

            <label for="nota">Nota / Concepto:</label>
            <textarea id="nota" name="nota" maxlength="500" placeholder="Escriba una nota opcional..."><?php echo get_valor('nota'); ?></textarea>

            <div class="acciones">
                <button type="submit" class="btn solid" id="btn-submit-pago">Registrar Pago</button>
                <a href="admin_pagos.php" class="btn cancel">Cancelar</a>
            </div>
        </form>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/gymember/php/includes/footer.php'); ?>

    <script>
        // Actualización del monto según plan seleccionado
        function actualizarMonto() {
            const select = document.getElementById('plan_id');
            const inputMonto = document.getElementById('monto');
            const precio = select.options[select.selectedIndex]?.dataset.precio;
            inputMonto.value = precio ? parseFloat(precio).toFixed(2) : '';
        }

        // CONTROL INTEGRADO DE RUTAS: Redirige automáticamente a la API externa si es Mercado Pago
        function evaluarMetodoPago() {
    const metodo = document.getElementById('metodo').value;
    const formulario = document.getElementById('form-pago');
    const botonSubmit = document.getElementById('btn-submit-pago');

    if (metodo === 'debito_mercadopago') {
        // Redirige al nuevo archivo dedicado en la misma carpeta
        formulario.action = "pago_mp.php"; 
        botonSubmit.textContent = "Proceder a Mercado Pago";
        botonSubmit.style.backgroundColor = "#009ee3";
    } else {
        formulario.action = "pago_procesar.php";
        botonSubmit.textContent = "Registrar Pago";
        botonSubmit.style.backgroundColor = "";
    }
}

        // Ejecutar al cargar la vista para conservar estados ante errores previos de validación
        document.addEventListener("DOMContentLoaded", evaluarMetodoPago);
    </script>
</body>
</html>