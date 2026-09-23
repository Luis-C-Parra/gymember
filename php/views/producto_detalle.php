<?php
// views/producto_detalle.php
session_start(); 
include('../../config/conexion.php'); 

$id_producto = $_GET['id'] ?? null;
$producto = null;

if (is_numeric($id_producto) && $id_producto > 0) {
    $stmt = $conn->prepare("SELECT id, nombre, descripcion, precio, stock, img FROM productos WHERE id = ? AND activo = 1");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows === 1) {
        $producto = $resultado->fetch_assoc();
    }
    $stmt->close();
}

if (!$producto) {
    if (isset($conn)) $conn->close();
    header('Location: tienda.php');
    exit;
}

// LÓGICA DE NAVEGACIÓN (ANTERIOR Y SIGUIENTE)
$id_actual = $producto['id']; 
$id_anterior = null;
$id_siguiente = null;

$sql_anterior = "SELECT id FROM productos WHERE id < ? AND activo = 1 ORDER BY id DESC LIMIT 1";
$stmt_anterior = $conn->prepare($sql_anterior);
$stmt_anterior->bind_param("i", $id_actual);
$stmt_anterior->execute();
$resultado_anterior = $stmt_anterior->get_result();
if ($resultado_anterior->num_rows > 0) {
    $id_anterior = $resultado_anterior->fetch_assoc()['id'];
}
$stmt_anterior->close();

$sql_siguiente = "SELECT id FROM productos WHERE id > ? AND activo = 1 ORDER BY id ASC LIMIT 1";
$stmt_siguiente = $conn->prepare($sql_siguiente);
$stmt_siguiente->bind_param("i", $id_actual);
$stmt_siguiente->execute();
$resultado_siguiente = $stmt_siguiente->get_result();
if ($resultado_siguiente->num_rows > 0) {
    $id_siguiente = $resultado_siguiente->fetch_assoc()['id'];
}
$stmt_siguiente->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo htmlspecialchars($producto['nombre']); ?> │ FitLife Gym</title>
    
    <link rel="icon" href="/gymember/assets/img/icono.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="/gymember/assets/css/styles.css" />
    <link rel="stylesheet" href="/gymember/assets/css/crud.css" />
    <link rel="stylesheet" href="/gymember/assets/css/navbar.css" />
    <link rel="stylesheet" href="/gymember/assets/css/footer.css" />
</head>
<body>
    
    <?php include('../includes/navbar.php'); ?>

    <main class="container" style="display: flex; gap: 40px; padding-top: 50px;">
        
        <div class="product-image" style="flex: 1;">
            <img src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" 
                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                 style="max-width: 100%; height: auto; border-radius: 12px;" />
        </div>
        
        <div class="product-details" style="flex: 1; padding-left: 20px;">
            
            <div class="detalle-nav" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                
                <?php if ($id_anterior): ?>
                    <a href="producto_detalle.php?id=<?php echo $id_anterior; ?>" 
                       style="color: #ff007c; text-decoration: none; font-weight: bold; padding: 5px;">
                       ← Anterior
                    </a>
                <?php else: ?>
                    <span style="color: #555; padding: 5px;">← Anterior</span>
                <?php endif; ?>

                <?php if ($id_siguiente): ?>
                    <a href="producto_detalle.php?id=<?php echo $id_siguiente; ?>" 
                       style="color: #ff007c; text-decoration: none; font-weight: bold; padding: 5px;">
                       Siguiente →
                    </a>
                <?php else: ?>
                    <span style="color: #555; padding: 5px;">Siguiente →</span>
                <?php endif; ?>
            </div>
            
            <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            <p style="font-size: 1.5em; color: #ff007c; font-weight: bold; margin: 15px 0;">
                $<?php echo number_format($producto['precio'], 2, ',', '.'); ?>
            </p>
            
            <p style="margin-bottom: 30px; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
            </p>

            <p style="font-weight: bold; margin-bottom: 20px;">
                Disponibilidad: 
                <?php if ($producto['stock'] > 0): ?>
                    <span style="color: green;">En Stock (<?php echo $producto['stock']; ?> unidades)</span>
                <?php else: ?>
                    <span style="color: red;">Agotado</span>
                <?php endif; ?>
            </p>

            <div class="consulta-info">
                <label for="" 
                       style="display: block; 
                              font-size: 1.15em; 
                              font-weight: bold; 
                              color: #ff007c; 
                              margin-bottom: 15px; 
                              margin-top: 20px; 
                              line-height: 1.4;">
                    Para la compra y método de pago de este producto,<br> consulte directamente en nuestros predios.
                </label>
            </div>
            
        

        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
</body>
</html>