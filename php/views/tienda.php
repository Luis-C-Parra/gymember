<?php
// views/tienda.php — Vista principal de la tienda con filtros, paginación y alertas de stock crítico

// --- INICIAR SESIÓN (MANDATORIO PARA DETECTAR EL ROL DEL ADMINISTRADOR) ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- INCLUIR CONEXIÓN ---
include('../../config/conexion.php'); 

// Manejo de error de conexión (CRÍTICO: Detiene la ejecución si falla)
if (!isset($conn) || $conn->connect_error) {
    die("Error de Conexión: La base de datos no está disponible. " . $conn->connect_error);
}

// ============================================
// LÓGICA DE FILTROS Y PAGINACIÓN
// ============================================

$total_paginas = 0; 
$productos_por_pagina = 4; // Mostrar 4 productos por página
$empezar_desde = 0;
$pagina_actual = 1;
$productos_tienda = []; // Inicializamos el array de productos

$busqueda = trim($_GET['busqueda'] ?? '');
$estado = $_GET['estado'] ?? '';

if (isset($_GET["pagina"]) && is_numeric($_GET["pagina"])) {
    $pagina_actual = (int)$_GET["pagina"];
    if($pagina_actual <= 0) {
        $pagina_actual = 1;
    }
}

$where_clause = "WHERE activo = 1";

if ($busqueda !== '') {
    $busqueda_safe = $conn->real_escape_string($busqueda);
    $where_clause .= " AND (nombre LIKE '%$busqueda_safe%' OR descripcion LIKE '%$busqueda_safe%')";
}

// 5. Contar el Total de Registros
$sql_total = "SELECT COUNT(id) AS total_productos FROM productos " . $where_clause;
$resultado_total = $conn->query($sql_total);

if ($resultado_total === false) {
    die("Error en la Consulta SQL: Verifique la tabla 'productos' y las columnas. Detalle: " . $conn->error);
}

$num_reg = $resultado_total->fetch_assoc()['total_productos'];
$total_paginas = ceil($num_reg / $productos_por_pagina);

if ($pagina_actual > $total_paginas && $total_paginas > 0) {
    $pagina_actual = $total_paginas;
} elseif ($total_paginas === 0) {
    $pagina_actual = 1;
}

$empezar_desde = ($pagina_actual - 1) * $productos_por_pagina;

// 7. Consulta Final (CRÍTICO: Añadimos la columna 'stock' solicitada para la regla de negocio)
$sql = "SELECT id, nombre, precio, categoria, img, descripcion, stock 
        FROM productos 
        " . $where_clause . " 
        ORDER BY id DESC 
        LIMIT $empezar_desde, $productos_por_pagina";

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos_tienda[] = $fila;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tienda │ FitLife Gym</title>

    <link rel="icon" href="assets/img/logo.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/gymember/assets/css/admin.css" />
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" />
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
    <link rel="stylesheet" href="/gymember/assets/css/tienda.css" />
</head>
<body>

    <?php include('../includes/navbar.php'); ?>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <div id="popupStock" class="modal-stock-alerta">
        <div class="modal-stock-content">
            <h3 class="modal-stock-title">⚠️ ALERTA: Stock Crítico Detectado</h3>
            <div class="modal-stock-body" id="popupStockBody"></div>
            <button class="btn-modal-close" onclick="closeStockModal()">Entendido</button>
            <div style="clear: both;"></div>
        </div>
    </div>
    <?php endif; ?>

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
                    <?php 
                    $current_filters = '&busqueda=' . urlencode($busqueda) . '&estado=' . urlencode($estado);
                    foreach ($productos_tienda as $producto): ?>
                        
                        <li class="card-item" 
                            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                            data-precio="<?php echo number_format($producto['precio'], 2, '.', ''); ?>"
                            data-categoria="<?php echo htmlspecialchars($producto['categoria']); ?>"
                            data-stock="<?php echo intval($producto['stock']); ?>">
                            
                            <a href="producto_detalle.php?id=<?php echo $producto['id']; ?>" class="card-item__link">
                                <div class="card-item__cover">
                                    <img src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" />
                                </div>
                                <div class="card-item__content">
                                    <img style="display:none;" src="" onerror="this.src='';">
                                    <p class="card-item__name"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                                    <p class="card-item__price">$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>
                                    <p class="card-item__category"><?php echo ucfirst(htmlspecialchars($producto['categoria'])); ?></p>
                                    <p class="card-item__stock-info">Disponibles: <strong><?php echo $producto['stock']; ?> un.</strong></p>
                                </div>
                            </a>
                        </li>

                    <?php endforeach; ?>
                <?php else: ?>
                    <li style="grid-column: 1 / -1; text-align: center; padding: 50px; color: var(--text-oscuro);">
                        No hay productos disponibles en este momento.
                    </li>
                <?php endif; ?>
            </ul>

            <div class="container_pag">
                <?php if ($total_paginas > 1): ?>
                <ul class="pagination">
                    <?php 
                    $previous_page = $pagina_actual - 1;
                    $link_prev = '?' . http_build_query(['pagina' => $previous_page, 'busqueda' => $busqueda, 'estado' => $estado]);
                    if ($pagina_actual > 1): ?>
                        <li class="pag_item"><a href="<?php echo $link_prev; ?>"><<</a></li>
                    <?php else: ?>
                        <li class="pag_item inactive"><a href="#"><<</a></li>
                    <?php endif; ?>

                    <?php 
                    for($i = 1; $i <= $total_paginas; $i++): 
                        $link_num = '?' . http_build_query(['pagina' => $i, 'busqueda' => $busqueda, 'estado' => $estado]);
                    ?>
                        <li class="pag_item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                            <a href="<?php echo $link_num; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php 
                    $next_page = $pagina_actual + 1;
                    $link_next = '?' . http_build_query(['pagina' => $next_page, 'busqueda' => $busqueda, 'estado' => $estado]);
                    if ($pagina_actual < $total_paginas): ?>
                        <li class="pag_item"><a href="<?php echo $link_next; ?>">>></a></li>
                    <?php else: ?>
                        <li class="pag_item inactive"><a href="#">>></a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <script src="/gymember/assets/js/tienda.js"></script>

    <?php
    // CONTROL PRIVILEGIADO: El bloque evaluador de stock crítico corre ÚNICAMENTE si el rol es 'admin' confirmed
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'):
        
        $hay_productos_criticos = false;
        $lista_alertas_html = "";

        if (!empty($productos_tienda)) {
            foreach ($productos_tienda as $prod) {
                if (isset($prod['stock']) && $prod['stock'] !== '' && $prod['stock'] !== null) {
                    $stock_num = intval($prod['stock']);
                    if ($stock_num >= 0 && $stock_num <= 5) {
                        $hay_productos_criticos = true;
                        $lista_alertas_html .= "• <strong>" . htmlspecialchars($prod['nombre']) . "</strong>: Actualmente posee solo <strong>" . $stock_num . "</strong> unidades.<br><br>";
                    }
                }
            }
        }

        if ($hay_productos_criticos):
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('popupStock');
            const bodyContainer = document.getElementById('popupStockBody');
            
            if (modal && bodyContainer) {
                bodyContainer.innerHTML = "<p>El sistema ha detectado artículos en la tienda que se encuentran en el límite crítico de reposición (menor o igual a 5 unidades):</p><br>" + 
                                          `<?php echo $lista_alertas_html; ?>` + 
                                          "<p style='margin-top:20px; font-size:0.9rem; color:#6c757d;'>Por favor, registre la compra de insumos para reponer el stock.</p>";
                
                modal.style.setProperty('display', 'flex', 'important');
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
            }
        });
    </script>
    <?php else: ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            closeStockModal();
        });
    </script>
    <?php 
        endif; 
    endif; 
    ?>

    <script>
        function closeStockModal() {
            const modal = document.getElementById('popupStock');
            if (modal) {
                modal.style.setProperty('display', 'none', 'important');
            }
        }
    </script>

    <?php include('../includes/footer.php'); ?>
</body>
</html>