<?php
// Incluir la conexión a la base de datos (Asumimos /config/conexion.php)
include('../../config/conexion.php'); 

// 2. OBTENER LOS PRODUCTOS DE LA BASE DE DATOS
$sql = "SELECT id, nombre, precio, categoria, img, descripcion FROM productos WHERE activo = 1 ORDER BY id DESC";
$resultado = $conn->query($sql);

$productos_tienda = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos_tienda[] = $fila;
    }
}

$conn->close();

// NOTA: 'productos.php' debe eliminarse si usas la DB, o asegúrate de que no cause conflictos.
// Elimina: include 'productos.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tienda │ FitLife Gym</title>

    <link rel="icon" href="assets/img/logo.ico" type="image/x-icon" />

    <link rel="stylesheet" href="/gymember/assets/css/admin.css" />
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" />
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
</head>
<body>


  <?php 
    // Incluir la barra de navegación.
    include('../includes/navbar.php'); 
    
    ?>
    

    <main class="container">

        <aside class="aside">
            <div class="filters">
                <label class="title_filters" for="buscar">BUSCAR</label>
                <input type="text" placeholder="Nombre o categoría" id="buscar" onkeyup="filterProducts()">
            </div>

            <div class="filters">
                <label class="title_filters" for="ordenar">ORDENAR POR</label>
                <select id="ordenar" onchange="sortProducts()">
                    <option value="default">Relevancia</option>
                    <option value="asc">Precio: menor a mayor</option>
                    <option value="desc">Precio: mayor a menor</option>
                </select>
            </div>

            <div class="filters">
                <p class="title_filters">PRECIO</p>
                <div>
                    <input type="number" id="min" placeholder="Mínimo" min="0" oninput="filterProducts()">
                    <span> — </span>
                    <input type="number" id="max" placeholder="Máximo" min="0" oninput="filterProducts()">
                </div>
            </div>

            <div class="filters">
                <p class="title_filters">CATEGORÍAS</p>
                <div class="check_filters">
                    <input type="checkbox" id="ropa" onchange="filterProducts()">
                    <label for="ropa">Ropa deportiva</label><br>

                    <input type="checkbox" id="suplementos" onchange="filterProducts()">
                    <label for="suplementos">Suplementos</label><br>

                    <input type="checkbox" id="accesorios" onchange="filterProducts()">
                    <label for="accesorios">Accesorios</label><br>
                </div>
            </div>

            <button class="btn-filtrar" onclick="filterProducts()">Aplicar filtros</button>
        </aside>

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
                                    <img src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" />
                                </div>
                                <div class="card-item__content">
                                    <p class="card-item__name"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                                    <p class="card-item__price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                                    <p class="card-item__category"><?php echo ucfirst(htmlspecialchars($producto['categoria'])); ?></p>
                                </div>
                            </a>
                        </li>

                        <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>" class="card-item__link">
    </a>

                    <?php endforeach; ?>
                <?php else: ?>
                    <li style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                        No hay productos disponibles en este momento.
                    </li>
                <?php endif; ?>
            </ul>

            <div class="container_pag">
                <ul class="pagination">
                    <li class="pag_item inactive"><a href="#"><<</a></li>
                    <li class="pag_item active"><a href="#">1</a></li>
                    <li class="pag_item"><a href="#">2</a></li>
                    <li class="pag_item"><a href="#">3</a></li>
                    <li class="pag_item"><a href="#">>></a></li>
                </ul>
            </div>
        </section>

    </main>

    <script src="/gymember/assets/js/tienda.js"></script>

    
        
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>
</html>