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
    <title>Gestión de Tienda | GYMEMBER Admin</title>

    <link rel="stylesheet" href="/gymember/assets/css/admin.css" />
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" />
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
    
</head>
<body>

    <?php 
    // Incluir la barra de navegación.
    include('../../includes/navbar.php'); 
    ?>

    <main class="admin-container">
        </main>

    <main class="admin-container">
        
        <div class="admin-header">
            <h1 class="admin-title">Listado de Productos (<?php echo count($productos); ?>)</h1>
            <a href="producto_crear.php" class="btn-nuevo">➕ Agregar Nuevo Producto</a>
        </div>
        
        <table class="product-table">
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
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td>
                                <?php if (!empty($producto['img'])): ?>
                                    <img src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-img">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($producto['categoria'])); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                            <td>
                                <span class="status-<?php echo $producto['activo'] ? 'activo' : 'inactivo'; ?>">
                                    <?php echo $producto['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="producto_editar.php?id=<?php echo $producto['id']; ?>" class="btn-editar">Editar</a>
                                <form action="producto_eliminar.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                    <button type="submit" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No hay productos registrados en la tienda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </main>
    
  
                    <?php include '../../includes/footer.php'; ?>
                  
</body>
</html>