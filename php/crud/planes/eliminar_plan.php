<?php
require_once __DIR__ . '/../../../config/conexion.php';
$id = $_GET['id'] ?? null;

if ($id) {
  $q = $conexion->prepare("DELETE FROM planes WHERE id=?");
  $q->bind_param("i", $id);
  $q->execute();
}

header("Location: planes.php");
exit;
?>
