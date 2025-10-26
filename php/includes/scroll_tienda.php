<?php
// includes/scroll_tienda.php - MÓDULO DE INCLUSIÓN
// Este script espera que la variable $productos_scroll esté definida por el archivo que lo incluye.
?>

<section id="tienda" class="shop-section">
  <div class="shop-wrapper"> 
    <div class="shop-header">
      <h2>Tienda</h2>
      <div class="scroll-buttons">
        <button class="scroll-btn left">⬅️</button>
        <button class="scroll-btn right">➡️</button>
      </div>
    </div>

    <div class="products-container">
        <?php if (!empty($productos_scroll)): ?>
            <?php foreach ($productos_scroll as $producto): ?>
                
                <div class="product-card">
                    <img 
    src="/gymember/<?php echo htmlspecialchars($producto['img']); ?>" 
    alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
    class="product-img" 
/>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($producto['descripcion'] ?? 'Producto de gimnasio', 0, 35)) . '...'; ?></p>
                        <p class="product-price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
                    </div>
                </div> 
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; width: 100%; color: #999;">No hay productos destacados disponibles.</p>
        <?php endif; ?>
    </div>
  </div>
</section>