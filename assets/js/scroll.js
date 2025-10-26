document.addEventListener('DOMContentLoaded', () => {
    // 1. Identificar el contenedor de los productos y los botones
    const productsContainer = document.querySelector('.products-container');
    const scrollLeftBtn = document.querySelector('.scroll-btn.left');
    const scrollRightBtn = document.querySelector('.scroll-btn.right');

    if (!productsContainer || !scrollLeftBtn || !scrollRightBtn) {
        return;
    }

    const scrollAmount = 300; // Cuánto desplazar por clic

    // 2. Función para el botón DERECHA
    scrollRightBtn.addEventListener('click', () => {
        productsContainer.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    });

    // 3. Función para el botón IZQUIERDA
    scrollLeftBtn.addEventListener('click', () => {
        productsContainer.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    });
});