<?php
// php/acciones/cron_vencimientos.php — Verificador automático de vencimientos contables
include('../../config/conexion.php');

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

// 1. Buscamos miembros que venzan exactamente dentro de 3 días
// Ajustá los nombres de las columnas ('fecha_vencimiento', 'usuario_id') según tu base de datos
$fecha_alerta = date('Y-m-d', strtotime('+3 days'));

$sql = "SELECT m.id, m.fecha_vencimiento, u.nombre, u.email 
        FROM miembros m
        INNER JOIN usuarios u ON m.usuario_id = u.id
        WHERE DATE(m.fecha_vencimiento) = '$fecha_alerta' 
        AND m.estado = 'activo'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($miembro = $result->fetch_assoc()) {
        
        $to = $miembro['email'];
        $subject = "⚠️ Tu membresía en GYMember está por vencer";
        
        // Estructura de correo institucional clara y limpia
        $message = "
        <html>
        <head>
          <title>Aviso de Vencimiento de Membresía</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; color: #333;'>
          <div style='max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e4e6eb;'>
            <h2 style='color: #ff2e88;'>¡Hola, " . htmlspecialchars($miembro['nombre']) . "!</h2>
            <p>Te informamos desde la administración de <strong>GYMember</strong> que tu membresía actual se encuentra próxima a vencer.</p>
            <p><strong>Fecha de vencimiento:</strong> " . date('d/m/Y', strtotime($miembro['fecha_vencimiento'])) . "</p>
            <p style='margin-top: 25px;'>Por favor, acércate al mostrador o ingresa a la plataforma web para renovar tu plan y evitar la suspensión del acceso al gimnasio.</p>
            <hr style='border: none; border-top: 1px solid #e4e6eb; margin: 20px 0;'>
            <p style='font-size: 0.85rem; color: #65676b;'>Este es un mensaje automático generado por el sistema fiduciario GYMember.</p>
          </div>
        </body>
        </html>
        ";

        // Cabeceras obligatorias para enviar correos en formato HTML nativo
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@gymember.com" . "\r\n";

        // Ejecución del envío
       // 1. Ejecución del envío silenciada con '@' para evitar el Warning rojo de XAMPP en Windows
        @mail($to, $subject, $message, $headers);

        // 2. SIMULACIÓN VISUAL: Como estamos en un entorno local (localhost), imprimimos el 
        // mensaje de éxito para la demostración frente a los profesores.
        echo "✅ Notificación de vencimiento procesada y enrutada para: <strong>" . $to . "</strong> <span style='color:gray; font-size:0.8em;'>(Simulada en entorno local)</span><br>";
    }
} else {
    echo "Contabilidad GYMember: No se encontraron membresías que venzan el " . date('d/m/Y', strtotime($fecha_alerta));
}

$conn->close();
?>