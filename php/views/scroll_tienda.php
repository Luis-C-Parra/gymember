<?php
// views/scroll_tienda.php

// 1. Configuración y Conexión de la Base de Datos
// Usamos __DIR__ para garantizar que la ruta de la conexión sea absoluta y correcta.
$ruta_conexion = __DIR__ . '/../../config/conexion.php';

// Verificación de conexión (opcional, pero ayuda a detener el script con un error claro)
if (!file_exists($ruta_conexion)) {
    die("ERROR: El archivo de conexión no se encuentra en la ruta esperada.");
}

include($ruta_conexion); 

if (!isset($conn) || $conn->connect_error) {
    die("ERROR: No se pudo establecer la conexión a la base de datos.");
}

// 2. Obtención de Productos (Consulta SQL sin LIMIT)
$sql = "SELECT id, nombre, descripcion, precio, img FROM productos ORDER BY fecha_creacion DESC";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("ERROR en la consulta SQL: " . $conn->error);
}

$productos_scroll = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos_scroll[] = $fila;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GYMember - Tienda y Contacto</title>
    
    <link rel="stylesheet" href="../../assets/css/scroll_tienda.css"/> 
    
</head>

<body>
    <section class="contact-section">
        <h2>Contactanos</h2>
        <p>Envíanos tu email para recibir actualizaciones y más información sobre nuestro gimnasio.</p>
        <form class="email-form">
            <input type="email" placeholder="Enter your email address" required />
            <button type="submit">Subscribe</button>
        </form>
    </section>

    <?php 
    // 3. INCLUSIÓN DEL MÓDULO DE LA TIENDA (Pasa la variable $productos_scroll)
    include('../includes/scroll_tienda.php'); 
    ?>

    <script src="../../assets/js/scroll.js"></script>
    
</body>
</html>