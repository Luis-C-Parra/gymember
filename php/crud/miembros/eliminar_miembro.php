<?php
// eliminar_miembro.php - elimina un registro de la tabla miembros
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';

$id = $_GET['id'] ?? null;

// si no se pasa id, vuelve a la lista
if (!$id) {
  header("Location: miembros.php");
  exit;
}

// elimina el miembro (y no el usuario, para no romper relación)
$stmt = $conexion->prepare("DELETE FROM miembros WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// redirige al listado
header("Location: miembros.php");
exit;
