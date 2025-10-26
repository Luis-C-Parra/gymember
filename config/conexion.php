<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "gymember_db";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_errno) {
        echo "Fallo al conectarse a MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        exit;
    }

    // Alias para compatibilidad
    $conexion = $conn;
?>
