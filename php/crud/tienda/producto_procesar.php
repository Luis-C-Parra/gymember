<?php
session_start();
// Usamos la ruta corregida
include('../../../config/conexion.php'); 

// 1. Verificar la acción
$accion = $_POST['accion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($accion)) {
    // Si no es POST o no hay acción, redirigir
    header('Location: admin_productos.php');
    exit;
}

// ===========================================
// 2. OBTENER Y SANEAR DATOS (¡MOVIDO AQUÍ!)
// ===========================================
$id = (int)($_POST['id'] ?? 0); // Necesario para Edición
$nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
$descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
$precio = (float)($_POST['precio'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$categoria = $conn->real_escape_string($_POST['categoria'] ?? '');
$activo = (int)($_POST['activo'] ?? 0);
$img_path = $_POST['img_actual'] ?? ''; // La imagen actual (solo para edición)

// ===========================================
// BLOQUE DE VALIDACIÓN DE DATOS
// ===========================================
$errores = [];

// 1. Validar campos obligatorios (nombre, precio, stock, categoria)
if (empty($nombre)) {
    $errores[] = "El nombre del producto es obligatorio.";
}
if ($precio <= 0) {
    $errores[] = "El precio debe ser un número positivo.";
}
if ($stock < 0) {
    $errores[] = "El stock no puede ser negativo.";
}
if (empty($categoria) || !in_array($categoria, ['ropa', 'suplementos', 'accesorios', 'otros'])) {
    $errores[] = "Debe seleccionar una categoría válida.";
}

// 2. Validar ID en caso de Edición
if ($accion === 'editar' && $id <= 0) {
    $errores[] = "El ID del producto a editar no es válido.";
}

// ===========================================
// MANEJO DE ERRORES Y REDIRECCIÓN
// ===========================================

if (!empty($errores)) {
    // Almacenar los errores y los datos ingresados en la sesión
    $_SESSION['mensaje'] = "❌ Error(es) de validación:<br>" . implode("<br>", $errores);
    $_SESSION['datos_formulario'] = $_POST; // Guarda todos los datos POST para precargar el formulario
    
    // Redirigir de vuelta al formulario de origen con los errores
    if ($accion === 'editar') {
        header('Location: producto_editar.php?id=' . $id);
    } else {
        header('Location: producto_crear.php');
    }
    exit;
}

// ===========================================
// 3. MANEJO DE LA SUBIDA DE ARCHIVOS
// ===========================================

if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $archivo_tmp = $_FILES['img']['tmp_name'];
    $nombre_archivo = basename($_FILES['img']['name']);
    
    // Directorio de subida (Ajustada la ruta relativa)
    $directorio_subida = '../../../assets/img/'; 
    
    // Generar un nombre único 
    $nombre_unico = time() . '_' . $nombre_archivo;
    $ruta_destino_absoluta = $directorio_subida . $nombre_unico;
    
    // Mover el archivo subido
    if (move_uploaded_file($archivo_tmp, $ruta_destino_absoluta)) {
        // Guardamos la RUTA RELATIVA AL PROYECTO (ej: assets/img/123_foto.jpg)
        $img_path = 'assets/img/' . $nombre_unico;
    } else {
        $_SESSION['mensaje'] = "❌ Error al mover el archivo de imagen.";
        // Si falla la subida, redirigir al listado o al formulario de origen
        $redireccion = ($accion === 'editar') ? 'producto_editar.php?id=' . $id : 'producto_crear.php';
        header('Location: ' . $redireccion);
        exit;
    }
}


// ===========================================
// LÓGICA DE CREACIÓN (C)
// ===========================================
if ($accion === 'crear') {
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria, img, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdissi", $nombre, $descripcion, $precio, $stock, $categoria, $img_path, $activo);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Producto '{$nombre}' creado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "❌ Error al crear el producto: " . $stmt->error;
    }
    $stmt->close();
} 
// ===========================================
// LÓGICA DE ACTUALIZACIÓN (U)
// ===========================================
else if ($accion === 'editar') {
    // Consulta UPDATE
    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria = ?, img = ?, activo = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    // bind_param: 7 campos de datos + 1 ID (ssdissii)
    $stmt->bind_param("ssdissii", $nombre, $descripcion, $precio, $stock, $categoria, $img_path, $activo, $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "✅ Producto '{$nombre}' actualizado exitosamente.";
    } else {
        $_SESSION['mensaje'] = "❌ Error al actualizar el producto: " . $stmt->error;
    }
    $stmt->close();
}


$conn->close();

// Redireccionar siempre a la lista de productos después de un proceso exitoso/fallido
header('Location: admin_productos.php');
exit;
?>