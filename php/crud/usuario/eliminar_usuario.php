<!-- USUARIO | ELIMINAR USUARIO -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/gymember/config/conexion.php';
$id = $_GET['id'] ?? null;

if ($id) {
    $query = $conexion->prepare("DELETE FROM usuarios WHERE id=?");
    $query->bind_param("i", $id);
    $query->execute();
}

header("Location: usuarios.php");
exit;
