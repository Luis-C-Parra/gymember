<!-- AUTH | AUTENTICACIÓN DE USUARIO -->
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function iniciar_sesion($usuario, $rol, $nombre = '') {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $rol;
    $_SESSION['nombre'] = $nombre ?: $usuario;
}

function usuario_logueado() {
    return isset($_SESSION['usuario']);
}

function datos_usuario() {
    return [
        'usuario' => $_SESSION['usuario'] ?? '',
        'rol' => $_SESSION['rol'] ?? '',
        'nombre' => $_SESSION['nombre'] ?? '',
    ];
}

function es_admin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function cerrar_sesion() {
    $_SESSION = [];
    session_destroy();
}
