<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "gymember_db";

    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_errno) {
        echo "Fallo al conectarse a MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        exit;
    }

    $conexion = $conn;
?>
