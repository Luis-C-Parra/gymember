<?php
// --- INICIO del archivo tienda.php ---

// 1. INCLUIR LA CONEXIÓN
// Ajusta la ruta a tu conexión. Asumimos que tienda.php está en /php/views/
// Para llegar a /config/conexion.php, subes 2 niveles (views, php) y entras a config/
include('../../config/conexion.php'); 

// 2. OBTENER LOS PRODUCTOS DE LA BASE DE DATOS
$sql = "SELECT id, nombre, precio, categoria, img FROM productos WHERE activo = 1 ORDER BY id DESC";
$resultado = $conn->query($sql);

$productos_tienda = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos_tienda[] = $fila;
    }
}

// Cierra la conexión inmediatamente
$conn->close();

// --- RESTO del HTML (Head, Body, Header) ...

?>
<!DOCTYPE html>
<html lang="es">
<head>
    </head>
<body>
    <main class="container">
        <section class="full-carousel">
          <ul class="slider__items" id="product-list">
            
            <?php if (count($productos_tienda) > 0): ?>
                <?php foreach ($productos_tienda as $producto): ?>
                    
                    <li class="card-item" 
                        data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                        data-precio="<?php echo htmlspecialchars($producto['precio']); ?>"
                        data-categoria="<?php echo htmlspecialchars($producto['categoria']); ?>">
                        
                        <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>" class="card-item__link">
                            <div class="card-item__cover">
                                <img src="../../<?php echo htmlspecialchars($producto['img']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" />
                            </div>
                            <div class="card-item__content">
                                <p class="card-item__name"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                                <p class="card-item__price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                                <p class="card-item__category"><?php echo ucfirst(htmlspecialchars($producto['categoria'])); ?></p>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    No hay productos disponibles en este momento.
                </li>
            <?php endif; ?>
            
          </ul>

          </section>

    </main>

    <script src="/gymember/assets/js/tienda.js"></script>

    <?php // include('/gymember/php/includes/footer.php'); ?> 
    
</body>
</html>