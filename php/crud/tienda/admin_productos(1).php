<?php
// Incluir la conexión a la base de datos
include('../../../config/conexion.php');

// Consulta para obtener todos los productos ordenados por ID (el más reciente primero)
$sql = "SELECT id, nombre, precio, categoria, stock, img, activo FROM productos ORDER BY id DESC";
$resultado = $conn->query($sql);

$productos = [];
if ($resultado->num_rows > 0) {
    // Almacenar los datos de cada fila en un array
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

$conn->close(); // Cerrar la conexión
?>
<!DOCTYPE html>
<html lang="es">
     
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>GyMember | Gestión de Productos</title>
    
    <!-- Ícono -->
    <link rel="icon" href="/gymember/assets/img/icono.ico" type="image/x-icon">

    <!-- CSS & Fuentes -->
    <link rel="stylesheet" href="/gymember/assets/css/styles.css" />
    <link rel="stylesheet" href="/gymember/assets/css/crud.css" />
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" />
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- NAVBAR -->
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

    <!-- cambié el nombre de clase de admin-container a container y borre lo repetido -->
    <main class="container">
        <h1 class="titulo">Gestión de Productos</h1> <!-- agregue título -->

        <!-- reemplacé la clase btn-nuevo por btn solid (= que los otros CRUDS) -->
        <div class="acciones">
            <a href="producto_crear.php" class="btn solid">+ Agregar Producto</a>
        </div>
        
        <!-- cambié la clase product-table por tabla (= que los otros CRUDS) -->
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                    <td><?= htmlspecialchars($producto['id']); ?></td>
                    <td>
                        <?php if (!empty($producto['img'])): ?>
                        <img src="/gymember/<?= htmlspecialchars($producto['img']); ?>" 
                            alt="<?= htmlspecialchars($producto['nombre']); ?>" 
                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                        <!-- use estilos inline -->
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($producto['nombre']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($producto['categoria'])); ?></td>
                    <td>$<?= number_format($producto['precio'], 2, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($producto['stock']); ?></td>
                    <td>
                        <span style="color: <?= $producto['activo'] ? 'lightgreen' : '#ffcc00'; ?>; font-weight: bold;">
                        <?= $producto['activo'] ? 'Activo' : 'Inactivo'; ?>
                        </span>
                    </td>
                    <td class="acciones-tabla">
                        <!-- cambié las clases de botones para que usen los estilos de crud y style -->
                        <a href="producto_editar.php?id=<?= $producto['id']; ?>" class="btn outline">Editar</a>

                        <form action="producto_eliminar.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= $producto['id']; ?>">
                        <button type="submit" class="btn outline btn-danger" 
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                            Eliminar
                        </button>
                        </form>
                    </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">No hay productos registrados en la tienda.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    
  
    <!-- FOOTER -->
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>

</html>