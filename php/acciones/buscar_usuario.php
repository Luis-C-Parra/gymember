<?php
// (acciones/buscar_usuario.php)
include('../../config/conexion.php');
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
$usuarios = [];

if (strlen($q) >= 2) {
    // Buscar por nombre o email (máx 10 resultados)
    $stmt = $conn->prepare("
        SELECT id, nombre, email 
        FROM usuarios 
        WHERE nombre LIKE CONCAT('%', ?, '%') 
           OR email LIKE CONCAT('%', ?, '%')
        LIMIT 10
    ");
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $usuarios[] = $row;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($usuarios);
?>
