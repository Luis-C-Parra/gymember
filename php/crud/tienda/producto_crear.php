<?php
session_start(); // Asegúrate de que session_start() vaya PRIMERO

// Incluimos la conexión. USAMOS 'conexion.php' 
include('../../../config/conexion.php');

// Array para las categorías (debe coincidir con tu ENUM en la base de datos)
$categorias = ['ropa', 'suplementos', 'accesorios', 'otros'];

// RECUPERAR DATOS Y ERRORES DE LA SESIÓN
$mensaje = $_SESSION['mensaje'] ?? '';
$datos_previos = $_SESSION['datos_formulario'] ?? [];

// Limpiar la sesión para que el mensaje y los datos no se muestren en recargas posteriores
unset($_SESSION['mensaje']); 
unset($_SESSION['datos_formulario']); 

// Función auxiliar para precargar un valor en el HTML
function get_valor($key, $default = '') {
    global $datos_previos;
    // Si hay datos previos en la sesión, los usa; si no, usa el valor por defecto ('')
    return htmlspecialchars($datos_previos[$key] ?? $default);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Crear Producto | GYMEMBER Admin</title>

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
        
        <h1 class="admin-title">Agregar Nuevo Producto</h1>
        
        <?php if ($mensaje): ?>
            <?php 
            // Determinar si es mensaje de éxito o error
            $clase_alerta = strpos($mensaje, '❌') !== false ? 'alerta-error' : 'alerta-exito';
            ?>
            <div class="<?php echo $clase_alerta; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form action="producto_procesar.php" method="POST" enctype="multipart/form-data" class="crud-form">

            <input type="hidden" name="accion" value="crear">

            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo get_valor('nombre'); ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo get_valor('descripcion'); ?></textarea>

            <label for="precio">Precio ($):</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo get_valor('precio'); ?>" required>

            <label for="stock">Stock Disponible:</label>
            <input type="number" id="stock" name="stock" min="0" value="<?php echo get_valor('stock'); ?>" required>

            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <option value="">-- Seleccione una --</option>
                <?php $cat_seleccionada = get_valor('categoria'); ?>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                        <?php echo ($cat_seleccionada === $cat) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="img">Imagen (Ruta o Subida de Archivo):</label>
            <input type="file" id="img" name="img" accept="image/*">
            <p style="font-size:0.85em; color: #666; margin-top: -10px; margin-bottom: 20px;">
                Se recomienda usar archivos JPEG o PNG.
            </p>

            <label for="activo">Estado:</label>
            <select id="activo" name="activo">
                <?php $activo_valor = get_valor('activo', '1'); // Predeterminado a '1' ?>
                <option value="1" <?php echo ($activo_valor == '1') ? 'selected' : ''; ?>>Activo (Visible en Tienda)</option>
                <option value="0" <?php echo ($activo_valor == '0') ? 'selected' : ''; ?>>Inactivo (Oculto)</option>
            </select>

            <button type="submit" class="btn-submit">Guardar Producto</button>
            <a href="admin_productos.php" class="btn-filtrar" style="display:inline-block; width: auto; background:#ccc; color:#333; margin-left: 10px;">Cancelar</a>

        </form>
    </main>

    <?php include '../../includes/footer.php'; ?> 
    
</body>
</html>
<?php $conn->close(); ?>