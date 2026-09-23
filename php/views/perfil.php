<?php
// views/perfil.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// VERIFICACIÓN DE SESIÓN
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$datosUsuario = [];
$miembro = null;
$ultimoPago = null;
$error_db = null;

// CONEXIÓN Y OBTENCIÓN DE DATOS PRINCIPALES
require_once __DIR__ . '/../../config/conexion.php';

if (!isset($conn) || $conn->connect_error) {
    $error_db = "Error de conexión al servidor de base de datos.";
} else {
    $query = $conn->prepare("SELECT id, usuario, nombre, email, rol, creado_en, foto FROM usuarios WHERE usuario = ?");
    
    if ($query === false) {
        $error_db = "Error SQL (usuarios): La tabla 'usuarios' o sus columnas no existen.";
    } else {
        $query->bind_param("s", $usuario);
        $query->execute();
        $datosUsuario = $query->get_result()->fetch_assoc();
        $query->close();
    }

    if ($datosUsuario && !$error_db) {
        $usuarioId = $datosUsuario['id'];
        
        $qMiembro = $conn->prepare("SELECT id, estado FROM miembros WHERE usuario_id = ?");
        
        if ($qMiembro === false) {
            $error_db .= "<br>Error SQL (miembros): La tabla 'miembros' o la columna 'usuario_id' no existe.";
        } else {
            // Buscar en la tabla miembros
            $qMiembro->bind_param("i", $usuarioId);
            $qMiembro->execute();
            $miembro = $qMiembro->get_result()->fetch_assoc();
            $qMiembro->close();
        }

        // ÚLTIMO PAGO
        $ultimoPago = null;
        $fechaVencimiento = null;

        if ($miembro && !$error_db) {
            $miembroId = $miembro['id'];
            $usuarioId = $datosUsuario['id'];

            // Último pago
            $qPago = $conn->prepare("
                SELECT monto, fecha 
                FROM pagos 
                WHERE usuario_id = ? 
                ORDER BY fecha DESC 
                LIMIT 1
            ");
            if ($qPago === false) {
                $error_db .= "<br>Error SQL (pagos): la consulta no se pudo preparar.";
            } else {
                $qPago->bind_param("i", $usuarioId);
                $qPago->execute();
                $ultimoPago = $qPago->get_result()->fetch_assoc();
                $qPago->close();
            }

            // ESTO NOS FALTA IMPLEMENTAAAR
            // $fechaVencimiento = $miembro['fecha_vencimiento'] ?? null;
        }
    }
    if (isset($conn)) $conn->close();
}

$fotoPerfil = $datosUsuario['foto'] ?? null;

if (!$datosUsuario && !$error_db) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// ruta y verificación de foto
$fotoPerfil = $datosUsuario['foto'] ?? null;
$fotoPathServer = $_SERVER['DOCUMENT_ROOT'] . '/gymember/assets/img/img_usuarios/' . ($fotoPerfil ?? '');
$fotoExiste = $fotoPerfil && file_exists($fotoPathServer);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mi perfil | GYMember</title>
    <link rel="icon" href="/gymember/assets/img/icono.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    <link rel="stylesheet" href="/gymember/assets/css/style.css">
    <link rel="stylesheet" href="/gymember/assets/css/perfil.css">
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css">
    <link rel="stylesheet" href="/gymember/assets/css/footer.css">
</head>

<body>
    <?php include(__DIR__ . '/../includes/navbar.php'); ?>

    <main class="perfil">
        <?php if ($error_db): ?>
        <div style="background: #660000; color: white; padding: 15px; margin: 20px; border-radius: 8px;">
            <strong>Error de Sistema:</strong> <?php echo htmlspecialchars($error_db); ?>
        </div>
        <?php endif; ?>
        
        <div class="perfil-card">
            <!-- FOTO -->
            <div class="perfil-foto">
                <?php if ($fotoExiste): ?>
                    <img src="/gymember/assets/img/img_usuarios/<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de <?= htmlspecialchars($datosUsuario['nombre'] ?? $datosUsuario['usuario']) ?>">
                <?php else: ?>
                    <iconify-icon icon="mdi:account-circle-outline"></iconify-icon>
                <?php endif; ?>
            </div>


            <h2><?= htmlspecialchars($datosUsuario['nombre'] ?? $datosUsuario['usuario']) ?></h2> 
            <p class="usuario">@<?= htmlspecialchars($datosUsuario['usuario'] ?? 'N/A') ?></p>

            <?php 
                $estado_membresia = $miembro['estado'] ?? 'no_registrado';
                $is_active = $estado_membresia === 'activo';
            ?>
            <?php if ($miembro): ?>
                <div class="estado <?php echo $is_active ? 'activo' : 'inactivo'; ?>">
                    <iconify-icon icon="mdi:<?php echo $is_active ? 'check-circle-outline' : 'close-circle-outline'; ?>"></iconify-icon>
                    Miembro <?php echo ucfirst($estado_membresia); ?>
                </div>
            <?php else: ?>
                <div class="estado inactivo">
                    <iconify-icon icon="mdi:close-circle-outline"></iconify-icon> No registrado como miembro
                </div>
            <?php endif; ?>

            <div class="info-group">
                <p><strong>Correo:</strong> <?= htmlspecialchars($datosUsuario['email'] ?? 'N/A') ?></p>
                <p><strong>Rol:</strong> <?= ucfirst($datosUsuario['rol'] ?? 'Invitado') ?></p>
                <p><strong>Cuenta creada:</strong> <?= date("d/m/Y", strtotime($datosUsuario['creado_en'] ?? 'now')) ?></p>
            </div>

            <?php if ($miembro): ?>
            <div class="info-group">
                <h3>Estado de Pago</h3>
                <?php if ($ultimoPago): ?>
                    <p><strong>Último Pago:</strong> $<?= number_format($ultimoPago['monto'], 2, ',', '.') ?></p>
                    <p><strong>Fecha del Pago:</strong> <?= date("d/m/Y", strtotime($ultimoPago['fecha'])) ?></p>
                    <!-- FALTA IMPLEMENTAR EN TABLA PAGOS
                    <?php if ($fechaVencimiento): ?>
                        <p><strong>Vencimiento Membresía:</strong> <?= date("d/m/Y", strtotime($fechaVencimiento)) ?></p>
                    <?php endif; ?> -->
                <?php else: ?>
                    <p>No hay pagos registrados.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>


            <button class="btn-editar" 
                    type="button" 
                    onclick="window.location.href='../acciones/editar_perfil.php?id=<?= $datosUsuario['id'] ?>'">
                Editar Perfil
            </button>

        </div>
    </main>

    <?php include(__DIR__ . '/../includes/footer.php'); ?>
</body>
</html>