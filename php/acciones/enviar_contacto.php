<?php
// gymember/php/acciones/enviar_contacto.php

define('SMTP_HOST', 'mail.serviciosya.com.ar');
define('SMTP_PORT', 587);
define('SMTP_USER', 'grupos@serviciosya.com.ar');
define('SMTP_PASS', 'B6UVDn@3pX');
define('EMAIL_REPLY', SMTP_USER);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email'])) {
    header('Location: /gymember/index.php#contacto?error=1');
    exit;
}

$email_cliente = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!$email_cliente || !filter_var($email_cliente, FILTER_VALIDATE_EMAIL)) {
    header('Location: /gymember/index.php#contacto?error=1');
    exit;
}

// ... resto de la lógica de envío SMTP (igual a tu código actual)

// 2. CONFIGURACIÓN DEL MENSAJE
$email_destino = EMAIL_REPLY;
$asunto = "Nueva Solicitud de Suscripción - GYMember";
$cuerpo_mensaje = "Se ha registrado una nueva solicitud de suscripción al newsletter.\n\n";
$cuerpo_mensaje .= "Correo del suscriptor: " . $email_cliente . "\n";
$cuerpo_mensaje .= "Fecha y Hora: " . date('d/m/Y H:i:s');

// 3. FUNCIÓN DE ENVÍO SMTP (Usando fsockopen - solo funciona si la función está habilitada)
function enviar_correo_smtp($to, $subject, $message) {
    $eol = "\r\n"; // Fin de línea estándar SMTP

    // Abrir conexión al servidor SMTP
    $smtp_conn = fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 10);
    if (!$smtp_conn) {
        // En caso de fallo de conexión (puerto cerrado, host incorrecto)
        return false;
    }

    // Comandos SMTP (Estándar)
    fputs($smtp_conn, "EHLO ". SMTP_HOST . $eol);
    // fputs($smtp_conn, "STARTTLS" . $eol); // 465 ya usa SSL
    fputs($smtp_conn, "AUTH LOGIN" . $eol);
    
    // Enviar credenciales codificadas en Base64
    fputs($smtp_conn, base64_encode(SMTP_USER) . $eol);
    fputs($smtp_conn, base64_encode(SMTP_PASS) . $eol);
    
    // Detalles del remitente y destinatario
    fputs($smtp_conn, "MAIL FROM: <" . SMTP_USER . ">" . $eol);
    fputs($smtp_conn, "RCPT TO: <" . $to . ">" . $eol);
    fputs($smtp_conn, "DATA" . $eol);
    
    // Cabeceras y Cuerpo del Mensaje
    fputs($smtp_conn, "Subject: $subject" . $eol);
    fputs($smtp_conn, "To: $to" . $eol);
    fputs($smtp_conn, "From: GYMember Contacto <" . SMTP_USER . ">" . $eol);
    fputs($smtp_conn, "Reply-To: <" . $GLOBALS['email_cliente'] . ">" . $eol);
    fputs($smtp_conn, "Content-Type: text/plain; charset=\"UTF-8\"" . $eol);
    fputs($smtp_conn, $message . $eol);
    fputs($smtp_conn, "." . $eol); // Fin del mensaje
    fputs($smtp_conn, "QUIT" . $eol);

    // Leer respuesta (opcional, pero ayuda a debuggear)
    while (!feof($smtp_conn)) {
        $response = fgets($smtp_conn, 1024);
        if (strpos($response, '250') === 0 || strpos($response, '334') === 0 || strpos($response, '220') === 0) {
            // Respuestas de éxito
            continue;
        }
        if (strpos($response, '5') === 0) {
            // Error SMTP
            fclose($smtp_conn);
            return false;
        }
    }
    
    fclose($smtp_conn);
    return true;
}

// 4. EJECUTAR ENVÍO Y REDIRIGIR
if (enviar_correo_smtp($email_destino, $asunto, $cuerpo_mensaje)) {
    header('Location: /gymember/index.php#contacto?ok=1');
} else {
    header('Location: /gymember/index.php#contacto?error=1');
}
exit;
?>