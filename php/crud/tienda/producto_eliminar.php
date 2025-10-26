<?php
session_start();
// Usamos la ruta corregida a la conexión
include('../../../config/conexion.php'); 

// 1. Verificar si se recibió el ID por POST (ya que se envía desde un formulario)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    // Si no es un POST o no hay ID, redirigir por seguridad
    header('Location: admin_productos.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    $_SESSION['mensaje'] = "❌ Error: ID de producto no válido para la eliminación.";
    header('Location: admin_productos.php');
    exit;
}

// 2. [Opcional] Borrar el archivo de imagen físico del servidor
// Para mantener limpio el servidor, es buena práctica borrar la imagen asociada
// (Bloque comentado, puedes descomentarlo si deseas implementar la eliminación de archivos)
/*
$stmt_img = $conn->prepare("SELECT img FROM productos WHERE id = ?");
$stmt_img->bind_param("i", $id);
$stmt_img->execute();
$resultado_img = $stmt_img->get_result();
$producto_img = $resultado_img->fetch_assoc();
$stmt_img->close();

if ($producto_img && !empty($producto_img['img'])) {
    // Ruta relativa desde el script procesador hasta la imagen (Sube 3 niveles)
    $ruta_imagen_fisica = '../../../' . $producto_img['img']; 
    if (file_exists($ruta_imagen_fisica)) {
        unlink($ruta_imagen_fisica); // Borra el archivo
    }
}
*/

// 3. Eliminar el registro de la base de datos
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['mensaje'] = "🗑️ Producto ID: {$id} eliminado exitosamente.";
} else {
    $_SESSION['mensaje'] = "❌ Error al eliminar el producto: " . $stmt->error;
}

$stmt->close();
$conn->close();

// 4. Redirigir siempre a la lista de productos
header('Location: admin_productos.php');
exit;
?>