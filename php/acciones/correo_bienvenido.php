<?php
// Este archivo debe estar en gymember/php/acciones/correo_bienvenido.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// La ruta a las librerias se mantiene igual, subiendo dos niveles (../../../librerias)
require __DIR__ . '/../../librerias/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../librerias/phpmailer/src/SMTP.php';
require __DIR__ . '/../../librerias/phpmailer/src/Exception.php';

function enviarCorreoBienvenida($email, $nombreUsuario) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // o tu servidor SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'luciodamianflores@gmail.com';
        $mail->Password   = 'wlbeouicdzvzkomx';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8'; // Asegura la compatibilidad con acentos

        $mail->setFrom('luciodamianflores@gmail.com', 'GYMember');
        $mail->addAddress($email, $nombreUsuario);

        // 🖼️ SE ELIMINA LA IMAGEN INCRUSTADA para evitar errores de ruta.
        // Se reemplaza por un encabezado de color sólido.

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenido a GYMember';

        $mail->Body = '
        <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 0; margin: 0;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" style="padding: 20px 0;">
                        
                        <!-- Contenedor Principal con Sombra -->
                        <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                            
                            <!-- Header / Encabezado (Color Sólido) -->
                            <tr>
                                <td align="center" style="background-color: #2c3e50; padding: 25px 0;">
                                    <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; letter-spacing: 2px;">GYMEMBER</h1>
                                </td>
                            </tr>
                            
                            <!-- Contenido del Cuerpo -->
                            <tr>
                                <td align="center" style="padding: 40px 30px;">
                                    <h2 style="color: #34495e; margin-top: 0; font-size: 24px; font-weight: 700;">
                                        ¡Bienvenido, ' . htmlspecialchars($nombreUsuario) . '!
                                    </h2>
                                    
                                    <p style="color: #34495e; font-size: 16px; line-height: 1.6; margin-bottom: 25px;">
                                        Gracias por registrarte y unirte a la comunidad GYMember. Estamos listos para ayudarte a alcanzar tus objetivos.
                                    </p>
                                    
                                    <p style="color: #34495e; font-size: 16px; line-height: 1.6; margin-bottom: 30px;">
                                        Utiliza el botón de abajo para acceder a tu área de miembro, ver tus planes y empezar a gestionar tu perfil.
                                    </p>
                                    
                                    <!-- Botón de Acción con Color de Acento -->
                                    <a href="http://localhost/gymember/index.php" 
                                       style="display:inline-block; padding: 15px 30px; background-color:#ff6aa9; color:#ffffff; text-decoration:none; border-radius:30px; margin-top:10px; font-weight: bold; font-size: 17px; letter-spacing: 0.5px; transition: background-color 0.3s;">
                                       INGRESAR A MI CUENTA
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td align="center" style="padding: 20px 30px; border-top: 1px solid #eeeeee;">
                                    <p style="margin: 0; font-size: 12px; color: #95a5a6;">
                                        Este es un mensaje automático. Por favor, no respondas a este correo.
                                    </p>
                                    <p style="margin: 5px 0 0 0; font-size: 14px; color: #7f8c8d;">
                                        &copy; ' . date('Y') . ' GYMember.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        ';

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Loguea el error de PHPMailer para ver la razón exacta 
        error_log('Error de envío de correo a ' . $email . '. PHPMailer Error: ' . $mail->ErrorInfo);
        
        // Retornamos el mensaje de error completo de PHPMailer
        return '❌ El correo no pudo enviarse. Razón: ' . $mail->ErrorInfo;
    }
}