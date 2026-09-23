document.addEventListener('DOMContentLoaded', () => {
    // 1. Identificar el contenedor de los productos y los botones
    const productsContainer = document.querySelector('.products-container');
    const scrollLeftBtn = document.querySelector('.scroll-btn.left');
    const scrollRightBtn = document.querySelector('.scroll-btn.right');

    // Salir si los elementos no se encuentran (ej. en otras páginas)
    if (!productsContainer || !scrollLeftBtn || !scrollRightBtn) {
        // console.warn("ScrollJS: No se encontraron los elementos necesarios para el scroll.");
        return;
    }

    // Definir la cantidad de desplazamiento por clic
    // Usamos un valor fijo, ajustado al ancho de la tarjeta (~200px) más el espacio (~15px)
    const scrollAmount = 215; 

    // 2. Función para el botón DERECHA (Siguiente)
    scrollRightBtn.addEventListener('click', () => {
        productsContainer.scrollBy({
            left: scrollAmount,
            behavior: 'smooth' // Desplazamiento suave
        });
    });

    // 3. Función para el botón IZQUIERDA (Anterior)
    scrollLeftBtn.addEventListener('click', () => {
        productsContainer.scrollBy({
            left: -scrollAmount, // Usar valor negativo
            behavior: 'smooth'
        });
    });
});