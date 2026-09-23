<?php
// views/scroll_tienda.php — Lógica principal de obtención de datos para el scroll.

// 1. Ruta absoluta al archivo de conexión
$root_path = $_SERVER['DOCUMENT_ROOT'] . '/gymember';
$conexion_path = $root_path . '/config/conexion.php';

$productos_scroll = [];
$error = null;

// 2. Verificar y cargar conexión
if (!file_exists($conexion_path)) {
    $conexion_path = __DIR__ . '/../../config/conexion.php';
    if (!file_exists($conexion_path)) {
        $error = "❌ Archivo de conexión no encontrado.";
    }
} 

if (!$error) {
    if (file_exists($conexion_path)) {
        include $conexion_path;
        if (isset($conn) && $conn->connect_error) {
            $error = "❌ Error de conexión: " . htmlspecialchars($conn->connect_error);
        } else {
            // 3. Consulta a la base de datos
            $sql = "SELECT id, nombre, descripcion, precio, img FROM productos ORDER BY fecha_creacion DESC";
            $resultado = $conn->query($sql);
            if ($resultado) {
                while ($fila = $resultado->fetch_assoc()) {
                    $productos_scroll[] = $fila;
                }
            } else {
                $error = "❌ Error en la consulta SQL: " . htmlspecialchars($conn->error);
            }
            if (isset($conn)) $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tienda - GYMember</title>
    <link rel="stylesheet" href="/gymember/assets/css/scroll_tienda.css"/>
</head>
<body>

    <section id="tienda" class="shop-section">
        <div class="shop-wrapper">
            <div class="shop-header">
                <h2>Productos</h2>
                <div class="scroll-buttons">
                    <button class="scroll-btn left">⬅️</button>
                    <button class="scroll-btn right">➡️</button>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php else: ?>
                <div class="products-container">
                    <?php if (!empty($productos_scroll)): ?>
                        <?php foreach ($productos_scroll as $producto): ?>
                            <div class="product-wrapper">
                                <div class="product-card">
                                    <a href="php/views/producto_detalle.php?id=<?php echo (int)$producto['id']; ?>">
                                        <img 
                                            src="/gymember/<?php echo htmlspecialchars($producto['img'] ?? 'assets/img/default.jpg'); ?>" 
                                            alt="<?php echo htmlspecialchars($producto['nombre'] ?? 'Producto'); ?>" 
                                            class="product-img" 
                                        />
                                        <div class="product-info">
                                            <h3><?php echo htmlspecialchars($producto['nombre'] ?? 'Sin nombre'); ?></h3>
                                            <p><?php echo htmlspecialchars(substr($producto['descripcion'] ?? 'Producto de gimnasio', 0, 35)) . '...'; ?></p>
                                        </div>
                                    </a>
                                </div>
                                <p class="product-price-outside">
                                    $<?php echo number_format($producto['precio'] ?? 0, 0, ',', '.'); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-message">No se encontraron productos. (Verifique si la tabla está vacía).</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="/gymember/assets/js/scroll.js"></script>
</body>
</html>