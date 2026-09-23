<?php
session_start();
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/correo_bienvenido.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);
    $confirmar = trim($_POST['confirmar']);

    if ($contrasena !== $confirmar) {
        $_SESSION['error_registro'] = "Las contraseñas no coinciden.";
        header("Location: ../views/register.php");
        exit;
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // --- MEJORA: VALIDACIÓN ESPECÍFICA ---
    // Buscamos si existe alguno de los dos
    $query = $conn->prepare("SELECT usuario, email FROM usuarios WHERE usuario = ? OR email = ?");
    $query->bind_param("ss", $usuario, $email);
    $query->execute();
    $resultado = $query->get_result();

    if ($resultado->num_rows > 0) {
        $usuario_existe = false;
        $email_existe = false;

        // Recorremos los resultados para ver cuál coincide
        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['usuario'] === $usuario) $usuario_existe = true;
            if ($fila['email'] === $email) $email_existe = true;
        }

        if ($usuario_existe && $email_existe) {
            $_SESSION['error_registro'] = "Tanto el nombre de usuario como el correo electrónico ya se encuentran registrados.";
        } elseif ($usuario_existe) {
            $_SESSION['error_registro'] = "El nombre de usuario '{$usuario}' ya está en uso. Por favor, probá con otro.";
        } else {
            $_SESSION['error_registro'] = "El correo electrónico '{$email}' ya está registrado. ¿Ya tenés una cuenta?";
        }

        header("Location: ../views/register.php");
        exit;
    }
    // ------------------------------------

    $rol_defecto = 'invitado';
    $insert = $conn->prepare("INSERT INTO usuarios (usuario, contrasena, rol, nombre, email) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $usuario, $contrasena_hash, $rol_defecto, $nombre, $email);

    if ($insert->execute()) {
        $_SESSION['usuario_id'] = $insert->insert_id;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $rol_defecto;
        $_SESSION['nombre'] = $nombre;

        if(enviarCorreoBienvenida($email, $nombre)) {
            $_SESSION['mensaje'] = "Registro exitoso. Se envió correo de bienvenida.";
        } else {
            $_SESSION['mensaje'] = "Registro exitoso, pero no se pudo enviar el correo.";
        }

        header("Location: ../../index.php");
        exit;
    } else {
        $_SESSION['error_registro'] = "Error al registrar el usuario: " . $conn->error;
        header("Location: ../views/register.php");
        exit;
    }
} else {
    header("Location: ../views/register.php");
    exit;
}