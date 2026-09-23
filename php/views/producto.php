<?php
// Incluir la lista de productos
include 'productos.php';

// Obtener el ID del producto desde la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar el producto por ID
$producto = null;
foreach ($productos as $p) {
    if ($p['id'] === $id) {
        $producto = $p;
        break;
    }
}

// Si no existe el producto, mostrar error
if (!$producto) {
    http_response_code(404);
    echo "<h1>Producto no encontrado</h1>";
    echo "<a href='tienda.php'>&larr; Volver a la tienda</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($producto['nombre']); ?> │ FitLife Gym</title>

  <!-- Favicon -->
  <link rel="icon" href="assets/img/logo.ico" type="image/x-icon" />

  <!-- CSS -->
  <link rel="stylesheet" href="css/tienda.css" />
  
  <style>
    /* Estilos específicos de producto */
    .product-detail {
      display: flex;
      gap: 40px;
      margin-top: 40px;
      padding: 20px;
      max-width: 1200px;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .product-detail img {
      width: 350px;
      height: 350px;
      object-fit: cover;
      border-radius: 12px;
    }

    .product-info {
      flex: 1;
    }

    .product-info h1 {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #111;
    }

    .product-info .price {
      font-size: 1.8rem;
      color: #ff007c;
      font-weight: bold;
      margin: 10px 0;
    }

    .product-info .category {
      font-size: 1rem;
      color: #666;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .product-info p {
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .btn-comprar {
      display: inline-block;
      padding: 12px 24px;
      background-color: #111;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      margin-top: 10px;
      transition: background-color 0.3s;
    }

    .btn-comprar:hover {
      background-color: #333;
    }

    .back-link {
      display: block;
      margin: 20px 0 0 20px;
      color: #666;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .back-link:hover {
      color: #ff007c;
    }

    @media (max-width: 768px) {
      .product-detail {
        flex-direction: column;
      }

      .product-detail img {
        width: 100%;
        height: auto;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Enlace de regreso -->
  <a href="tienda.php" class="back-link">&larr; Volver a la tienda</a>

  <!-- Detalle del producto -->
  <section class="product-detail">
    <img 
      src="<?php echo $producto['img']; ?>" 
      alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
      onerror="this.src='https://via.placeholder.com/350x350?text=Sin+imagen';"
    >

    <div class="product-info">
      <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
      <p class="price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
      <p class="category"><?php echo ucfirst($producto['categoria']); ?></p>

      <?php if (isset($producto['etiqueta']) && !empty($producto['etiqueta'])): ?>
        <p><strong>Etiqueta:</strong> <span style="color:#ff007c;"><?php echo $producto['etiqueta']; ?></span></p>
      <?php endif; ?>

    
      <p>
        <?php
      
$desc = '';
$categoria = $producto['categoria'];

if ($categoria === 'ropa') {
    $desc = 'Prenda deportiva de alta calidad, ideal para entrenamientos intensos. Diseño ergonómico, tejido transpirable y máxima comodidad.';
} elseif ($categoria === 'suplementos') {
    $desc = 'Suplemento proteico de alto rendimiento, ideal para aumentar masa muscular y mejorar la recuperación post-entreno.';
} elseif ($categoria === 'accesorios') {
    $desc = 'Accesorio práctico y duradero, perfecto para acompañarte en cada rutina. Material resistente y fácil de limpiar.';
} else {
    $desc = 'Producto ideal para potenciar tu experiencia en el gimnasio.';
}
          echo $desc;
        ?>
      </p>

      <a href="#" class="btn-comprar">Agregar al carrito</a>
    </div>
  </section>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

</body>
</html>