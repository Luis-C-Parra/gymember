<?php
// pago_mp.php — Procesador exclusivo para la pasarela de Mercado Pago (Solo Débito)
include('../../../config/conexion.php'); 

if (!isset($conn) || $conn->connect_error) {
    die("Error de conexión a la base de datos.");
}

session_start();

// 1. Validar que la petición provenga del formulario y contenga datos válidos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id']) && !empty($_POST['usuario_id'])) {
    
    $usuario_id = intval($_POST['usuario_id']);
    $plan_id = intval($_POST['plan_id']);
    
    // Limpieza estricta del monto monetario para pasarlo como flotante puro
    $monto_limpio = str_replace(['$', ' ', ','], ['', '', '.'], $_POST['monto']);
    $monto = floatval($monto_limpio);

    // Obtener los datos del usuario para el perfil del comprador (Payer)
    $sql_u = "SELECT nombre, email FROM usuarios WHERE id = $usuario_id";
    $res_u = $conn->query($sql_u);
    $usuario = $res_u ? $res_u->fetch_assoc() : null;

    // Control de emails compatibles con el entorno Sandbox de Mercado Pago
    $payer_email = (!empty($usuario['email']) && filter_var($usuario['email'], FILTER_VALIDATE_EMAIL)) 
                    ? trim($usuario['email']) 
                    : "test_user_60831212@testuser.com"; // Email genérico sandbox
                    
    $payer_name = !empty($usuario['nombre']) ? trim($usuario['nombre']) : "Miembro GyMember";

    if ($monto <= 0) {
        $_SESSION['mensaje'] = "❌ Error de validación: El monto del plan debe ser un número mayor a cero.";
        header("Location: pago_crear.php");
        exit;
    }

    // 2. CONFIGURACIÓN DE LA API DE MERCADO PAGO CON TUS CREDENCIALES REALES
    $access_token = "APP_USR-3139382056169593-060820-6675d42c2ea543d38a1b4f70ff2ec5c8-3458884631"; 

    // Concatenamos los identificadores necesarios en la referencia externa como un string plano
    $referencia_externa = $usuario_id . "-" . $plan_id . "-" . number_format($monto, 2, '.', '');

    // 3. ESTRUCTURA DE LA PREFERENCIA OPTIMIZADA
    $preference_data = [
        "items" => [
            [
                "title" => "Membresia GYMember - Plan ID: " . $plan_id,
                "quantity" => 1,
                "unit_price" => (float)$monto,
                "currency_id" => "ARS"
            ]
        ],
        "payer" => [
            "name" => $payer_name,
            "email" => $payer_email
        ],
        // RESTRICCIÓN OPERATIVA EXIGIDA: Bloquear Crédito, permitir SOLO Débito
        "payment_methods" => [
            "excluded_payment_types" => [
                ["id" => "credit_card"] 
            ],
            "installments" => 1 
        ],
        // Mantenemos las back_urls para el botón "Volver al sitio"
        "back_urls" => [
            "success" => "http://localhost/gymember/php/crud/pagos/webhook_mp.php",
            "failure" => "http://localhost/gymember/php/crud/pagos/pago_crear.php",
            "pending" => "http://localhost/gymember/php/crud/pagos/pago_crear.php"
        ],
        // SOLUCIÓN DEFINITIVA: Desactivamos el auto_return para eludir las restricciones de seguridad de cuentas locales en localhost
        "external_reference" => (string)$referencia_externa
    ];

    // 4. ENVÍO DE PETICIÓN cURL A LOS SERVIDORES DE MERCADO PAGO
    $ch = curl_init("https://api.mercadopago.com/checkout/preferences");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $access_token,
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference_data));
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    // 5. REDIRECCIÓN AUTOMÁTICA AL CHECKOUT SEGURO
    // SOLUCIÓN DEFINITIVA: Se añade la validación del código 201 (Created) estándar de la API de MP
    if (($http_code === 200 || $http_code === 201) && isset($result['init_point'])) {
        header("Location: " . $result['init_point']);
        exit;
    } else {
        // Captura avanzada del error real de la API si el código fuese un verdadero fallo (400, 401, 403, 500)
        $detalles_error = isset($result['message']) ? " - Detalle: " . $result['message'] : "";
        $_SESSION['mensaje'] = "❌ Error real en Mercado Pago (Código HTTP obtenido: " . $http_code . ")" . $detalles_error . ".";
        $_SESSION['datos_formulario'] = $_POST;
        header("Location: pago_crear.php");
        exit;
    }

} else {
    header("Location: pago_crear.php");
    exit;
}
?>