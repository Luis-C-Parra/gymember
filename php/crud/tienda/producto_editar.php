<?php
session_start();
// Usamos la ruta corregida
include('../../../config/conexion.php'); 

// Array de categorías (debe coincidir con tu ENUM)
$categorias = ['ropa', 'suplementos', 'accesorios', 'otros'];

// ===============================================
// 1. OBTENER DATOS DE LA DB Y SESIÓN
// ===============================================
$id = $_GET['id'] ?? null;
$producto = null;

// Recuperar datos y errores de la sesión si existen
$mensaje = $_SESSION['mensaje'] ?? '';
$datos_previos = $_SESSION['datos_formulario'] ?? [];

unset($_SESSION['mensaje']); 
unset($_SESSION['datos_formulario']); 

// 2. Cargar el producto original de la DB
if (!$id || !is_numeric($id)) {
    header('Location: admin_productos.php');
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre, descripcion, precio, stock, categoria, img, activo FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $producto = $resultado->fetch_assoc();
} else {
    // Si el producto no existe, redirigir
    header('Location: admin_productos.php');
    exit;
}

$stmt->close();
$conn->close(); 

// Función auxiliar para obtener el valor correcto (sesión > DB)
function get_valor($key, $db_valor, $datos_previos) {
    // Si hay datos en la sesión para esta clave, úsalos; si no, usa el valor de la DB.
    return htmlspecialchars($datos_previos[$key] ?? $db_valor);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Editar Producto: <?php echo htmlspecialchars($producto['nombre']); ?> | GYMEMBER Admin</title>

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
        
        <h1 class="admin-title">Editar Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h1>
        
        <?php if ($mensaje): ?>
            <?php 
            // Determinar si es mensaje de éxito o error
            $clase_alerta = strpos($mensaje, '❌') !== false ? 'alerta-error' : 'alerta-exito';
            ?>
            <div class="<?php echo $clase_alerta; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form action="producto_procesar.php" method="POST" enctype="multipart/form-data" class="crud-form">

            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
            <input type="hidden" name="img_actual" value="<?php echo htmlspecialchars($producto['img']); ?>">

            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?php echo get_valor('nombre', $producto['nombre'], $datos_previos); ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo get_valor('descripcion', $producto['descripcion'], $datos_previos); ?></textarea>

            <label for="precio">Precio ($):</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0" 
                   value="<?php echo get_valor('precio', $producto['precio'], $datos_previos); ?>" required>

            <label for="stock">Stock Disponible:</label>
            <input type="number" id="stock" name="stock" min="0" 
                   value="<?php echo get_valor('stock', $producto['stock'], $datos_previos); ?>" required>

            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">-- Seleccione una --</option>
                <?php $cat_seleccionada = get_valor('categoria', $producto['categoria'], $datos_previos); ?>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                        <?php echo ($cat_seleccionada === $cat) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Imagen Actual:</label>
            <?php if (!empty($producto['img'])): ?>
                <img src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" alt="Imagen actual" class="product-img" style="display: block; margin-bottom: 10px;">
            <?php else: ?>
                <p>No hay imagen cargada.</p>
            <?php endif; ?>

            <label for="img">Subir Nueva Imagen (dejar vacío para mantener la actual):</label>
            <input type="file" id="img" name="img" accept="image/*">

            <label for="activo">Estado:</label>
            <select id="activo" name="activo">
                <?php $activo_valor = get_valor('activo', $producto['activo'], $datos_previos); ?>
                <option value="1" <?php echo ($activo_valor == '1') ? 'selected' : ''; ?>>Activo (Visible en Tienda)</option>
                <option value="0" <?php echo ($activo_valor == '0') ? 'selected' : ''; ?>>Inactivo (Oculto)</option>
            </select>

            <button type="submit" class="btn-submit">Guardar Cambios</button>
            <a href="admin_productos.php" class="btn-filtrar" style="display:inline-block; width: auto; background:#ccc; color:#333; margin-left: 10px;">Cancelar</a>

        </form>
    </main>

    <?php include '../../includes/footer.php'; ?> 
    
</body>
</html>