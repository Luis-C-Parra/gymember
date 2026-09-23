<?php 
session_start();

require_once __DIR__ . '/../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

$input = trim($_POST['usuario'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($input === '' || $password === '') {
    echo "<script>alert('Por favor, completá usuario o email y contraseña.'); window.location='../views/login.php';</script>";
    exit;
}

// Buscar por usuario o email
$sql = "SELECT id, usuario, nombre, email, rol, contrasena 
        FROM usuarios 
        WHERE usuario = ? OR email = ? 
        LIMIT 1";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    error_log("Error prepare login: " . $conexion->error);
    echo "<script>alert('Error interno.'); window.location='../views/login.php';</script>";
    exit;
}

$stmt->bind_param("ss", $input, $input);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<script>alert('Usuario no encontrado.'); window.location='../views/login.php';</script>";
    exit;
}

$user = $res->fetch_assoc();

// Comprobación de hash seguro
if (password_verify($password, $user['contrasena'])) {
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['rol'] = $user['rol'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['usuario'] = $user['usuario'];

    header('Location: ../../index.php');
    exit;
} else {
    echo "<script>alert('Contraseña incorrecta'); window.location='../views/login.php';</script>";
    exit;
}
