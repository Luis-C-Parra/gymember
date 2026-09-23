<!-- producto_crear.php -->
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
    <title>GYMember | Crear Producto</title>

    <!-- Ícono -->
    <link rel="icon" href="/gymember/assets/img/icono.ico">   

    <link rel="stylesheet" href="/gymember/assets/css/styles.css">
    <link rel="stylesheet" href="/gymember/assets/css/crud.css">
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" /> 
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">  
</head>

<body>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/navbar.php'); ?>

  <main class="container">
    <h1 class="titulo">Agregar Nuevo Producto</h1>

    <?php if ($mensaje): ?>
      <?php $clase_alerta = strpos($mensaje, '❌') !== false ? 'alerta-error' : 'alerta-exito'; ?>
      <div class="<?= $clase_alerta; ?>"><?= $mensaje; ?></div>
    <?php endif; ?>

    <form action="producto_procesar.php" method="POST" enctype="multipart/form-data" class="formulario">
      <input type="hidden" name="accion" value="crear">

      <label for="nombre">Nombre del Producto:</label>
      <input type="text" id="nombre" name="nombre" value="<?= get_valor('nombre'); ?>" required>

      <label for="descripcion">Descripción:</label>
      <textarea id="descripcion" name="descripcion"><?= get_valor('descripcion'); ?></textarea>

      <label for="precio">Precio ($):</label>
      <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?= get_valor('precio'); ?>" required>

      <label for="stock">Stock Disponible:</label>
      <input type="number" id="stock" name="stock" min="0" value="<?= get_valor('stock'); ?>" required>

      <label for="categoria">Categoría:</label>
      <select id="categoria" name="categoria" required>
        <option value="">-- Seleccione una --</option>
        <?php $cat_sel = get_valor('categoria'); ?>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= htmlspecialchars($cat); ?>" <?= ($cat_sel === $cat) ? 'selected' : ''; ?>>
            <?= ucfirst($cat); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="img">Imagen:</label>
      <input type="file" id="img" name="img" accept="image/*">
      <p class="text-muted" style="font-size:0.85em; margin-top:-8px;">Se recomienda usar JPEG o PNG.</p>

      <label for="activo">Estado:</label>
      <?php $activo_valor = get_valor('activo', '1'); ?>
      <select id="activo" name="activo">
        <option value="1" <?= ($activo_valor == '1') ? 'selected' : ''; ?>>Activo (Visible en Tienda)</option>
        <option value="0" <?= ($activo_valor == '0') ? 'selected' : ''; ?>>Inactivo (Oculto)</option>
      </select>

      <div class="acciones">
        <button type="submit" class="btn solid">Guardar Producto</button>
        <a href="admin_productos.php" class="btn outline">Cancelar</a>
      </div>
    </form>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/gymember/php/includes/footer.php'); ?>
</body>

</html>

<?php $conn->close(); ?>