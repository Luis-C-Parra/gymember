<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: miembros.php");
  exit;
}

// Obtener planes activos
$planes = $conexion->query("SELECT id, nombre, precio, periodo FROM planes WHERE activo = 1")->fetch_all(MYSQLI_ASSOC);

// Traer datos del miembro
$sql = "SELECT * FROM miembros WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$miembro = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $estado = $_POST['estado'];
  $plan_id = $_POST['plan_id'] ?: null;
  $fecha_inicio = $_POST['fecha_inicio'] ?: null;
  $fecha_vencimiento = $_POST['fecha_vencimiento'] ?: null;

  $upd = $conexion->prepare("UPDATE miembros SET estado=?, plan_id=?, fecha_inicio=?, fecha_vencimiento=? WHERE id=?");
  $upd->bind_param("sissi", $estado, $plan_id, $fecha_inicio, $fecha_vencimiento, $id);
  $upd->execute();

  header("Location: miembros.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>GYMember | Editar Miembro</title>
  <link rel="icon" href="/gymember/assets/img/icono.ico">
  <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
  <link rel="stylesheet" href="/gymember/assets/css/footer.css">
  <link rel="stylesheet" href="/gymember/assets/css/style.css">
  <link rel="stylesheet" href="/gymember/assets/css/crud.css">
</head>
<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <div class="container">
    <h1 class="titulo">Editar Miembro</h1>

    <form class="formulario" method="POST">
      <label>Estado:</label>
      <select name="estado" required>
        <option value="activo" <?= $miembro['estado']==='activo'?'selected':'' ?>>Activo</option>
        <option value="inactivo" <?= $miembro['estado']==='inactivo'?'selected':'' ?>>Inactivo</option>
        <option value="suspendido" <?= $miembro['estado']==='suspendido'?'selected':'' ?>>Suspendido</option>
      </select>

      <label>Plan Asignado:</label>
      <select name="plan_id">
        <option value="">— Ninguno —</option>
        <?php foreach ($planes as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $miembro['plan_id']==$p['id']?'selected':'' ?>>
            <?= $p['nombre'] ?> (<?= ucfirst($p['periodo']) ?> - $<?= number_format($p['precio'],0,',','.') ?>)
          </option>
        <?php endforeach; ?>
      </select>

      <label>Fecha Inicio:</label>
      <input type="date" name="fecha_inicio" value="<?= $miembro['fecha_inicio'] ?>">

      <label>Fecha Vencimiento:</label>
      <input type="date" name="fecha_vencimiento" value="<?= $miembro['fecha_vencimiento'] ?>">

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar Cambios</button>
        <a href="miembros.php" class="btn outline">Cancelar</a>
      </div>
    </form>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>
