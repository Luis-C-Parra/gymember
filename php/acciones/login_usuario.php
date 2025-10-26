<?php
session_start();
include(__DIR__ . '/../../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Buscar usuario por nombre o email
    $query = "SELECT * FROM usuarios WHERE usuario = '$usuario' OR email = '$usuario' LIMIT 1";
    $resultado = $conn->query($query);

    if ($resultado && $resultado->num_rows > 0) {
        $user = $resultado->fetch_assoc();

        if ($password === $user['contrasena']) {

            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según rol
            if ($user['rol'] === 'admin') {
                header('Location: ../../index.php');
            } else {
                header('Location: ../../index.php');
            }
            exit;

        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location='../views/login.php';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location='../views/login.php';</script>";
    }
}
?>
