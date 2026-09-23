<?php
// pago_editar.php - FORMULARIO PARA MODIFICAR PAGO
session_start();
include('../../../config/conexion.php');

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

// Validar ID de pago
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
  header('Location: admin_pagos.php');
  exit;
}

// Consultar datos
$stmt = $conn->prepare("
    SELECT p.*, u.id AS usuario_id, u.nombre AS nombre_usuario, pl.nombre AS nombre_plan 
    FROM pagos p
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    LEFT JOIN planes pl ON p.plan_id = pl.id
    WHERE p.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$pago = $resultado->fetch_assoc();
$stmt->close();

if (!$pago) {
  header('Location: admin_pagos.php');
  exit;
}

// Llamar listas de usuarios y planes
$usuarios = $conn->query("SELECT id, usuario, nombre, email FROM usuarios ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);
$planes = $conn->query("SELECT id, nombre, precio FROM planes WHERE activo = 1 ORDER BY precio ASC")->fetch_all(MYSQLI_ASSOC);

$mensaje = $_SESSION['mensaje'] ?? '';
$datos_previos = $_SESSION['datos_formulario'] ?? [];
unset($_SESSION['mensaje'], $_SESSION['datos_formulario']);

function get_valor($key, $db, $datos_previos) {
  return htmlspecialchars($datos_previos[$key] ?? $db);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GyMember | Editar Pago ID: <?= $pago['id'] ?></title>

  <!-- Ícono y fuentes -->
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/gymember/assets/css/styles.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/gymember/php/includes/navbar.php'); ?>

    <main class="container">
    <h1 class="titulo">Editar Pago - ID <?= $pago['id'] ?></h1>

    <?php if($mensaje): ?>
        <div class="<?= strpos($mensaje,'❌')!==false?'alerta-error':'alerta-exito' ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <form action="pago_procesar.php" method="POST" class="formulario">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $pago['id'] ?>">

        <label for="usuario_id">Usuario:</label>
        <input type="text" id="buscadorUsuario" placeholder="Buscar usuario..." onkeyup="filtrarUsuarios()" autocomplete="off">
        <select id="usuario_id" name="usuario_id" required>
        <option value="">-- Seleccionar Usuario --</option>
        <?php foreach($usuarios as $u): ?>
            <option value="<?= $u['id'] ?>" <?= ($u['id']==$pago['usuario_id'])?'selected':'' ?>>
            <?= htmlspecialchars($u['nombre'].' ('.$u['usuario'].')') ?>
            </option>
        <?php endforeach; ?>
        </select>

        <label for="plan_id">Plan:</label>
        <input type="text" id="buscadorPlan" placeholder="Buscar plan..." onkeyup="filtrarPlanes()" autocomplete="off">
        <select id="plan_id" name="plan_id" required onchange="actualizarPrecio()">
        <option value="">-- Seleccionar Plan --</option>
        <?php foreach($planes as $pl): ?>
            <option value="<?= $pl['id'] ?>" data-precio="<?= $pl['precio'] ?>" <?= ($pl['id']==$pago['plan_id'])?'selected':'' ?>>
            <?= htmlspecialchars($pl['nombre']) ?> ($<?= number_format($pl['precio'],0,',','.') ?>)
            </option>
        <?php endforeach; ?>
        </select>

        <label for="monto">Monto ($):</label>
        <input type="number" id="monto" name="monto" step="0.01" min="0.01"
            value="<?= get_valor('monto', $pago['monto'], $datos_previos); ?>" required>

        <label for="metodo">Método:</label>
        <?php $metodos = ['efectivo','transferencia','tarjeta']; ?>
        <select id="metodo" name="metodo" required>
        <option value="">-- Seleccionar --</option>
        <?php foreach($metodos as $m): ?>
            <option value="<?= $m ?>" <?= ($m==$pago['metodo'])?'selected':'' ?>><?= ucfirst($m) ?></option>
        <?php endforeach; ?>
        </select>

        <label for="nota">Nota / Concepto:</label>
        <textarea id="nota" name="nota"><?= get_valor('nota', $pago['nota'], $datos_previos); ?></textarea>

        <button type="submit" class="btn-submit">Guardar Cambios</button>
        <a href="admin_pagos.php" class="btn-filtrar" style="margin-left:10px;">Cancelar</a>
    </form>
    </main>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/gymember/php/includes/footer.php'); ?>

    <script>
        function actualizarPrecio(){
        const plan = document.getElementById('plan_id');
        const monto = document.getElementById('monto');
        const precio = plan.options[plan.selectedIndex]?.dataset.precio;
        if(precio) monto.value = precio;
        }

        function filtrarUsuarios(){
        const input = document.getElementById('buscadorUsuario').value.toLowerCase();
        const opciones = document.getElementById('usuario_id').options;
        for(let opt of opciones){
            if(opt.value === "") continue;
            opt.style.display = opt.text.toLowerCase().includes(input) ? "block" : "none";
        }
        }

        function filtrarPlanes(){
        const input = document.getElementById('buscadorPlan').value.toLowerCase();
        const opciones = document.getElementById('plan_id').options;
        for(let opt of opciones){
            if(opt.value === "") continue;
            opt.style.display = opt.text.toLowerCase().includes(input) ? "block" : "none";
        }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>