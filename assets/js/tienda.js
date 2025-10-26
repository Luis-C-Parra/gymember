document.addEventListener('DOMContentLoaded', () => {
    // Referencia al contenedor principal de la lista de productos
    const productList = document.getElementById('product-list');
    
    // Si la lista no existe, salimos
    if (!productList) return;

    // Hacemos las funciones accesibles globalmente desde el HTML (onkeyup, onchange)
    window.filterProducts = filterProducts;
    window.sortProducts = sortProducts;
    
    // Inicializar al cargar para asegurar el orden
    sortProducts(); 
});


function filterProducts() {
    try {
        const searchTerm = document.getElementById('buscar').value.toLowerCase();
        const minPrice = parseFloat(document.getElementById('min').value) || 0;
        const maxPrice = parseFloat(document.getElementById('max').value) || Infinity;
        
        const categories = Array.from(document.querySelectorAll('.check_filters input:checked'))
                                .map(cb => cb.id);

        const allProducts = Array.from(document.querySelectorAll('#product-list .card-item'));

        allProducts.forEach(product => {
            // Asegúrate de que los data-atributos existen antes de llamar a toLowerCase()
            const name = product.dataset.nombre ? product.dataset.nombre.toLowerCase() : '';
            const price = parseFloat(product.dataset.precio || 0);
            const category = product.dataset.categoria ? product.dataset.categoria.toLowerCase() : '';
            
            let matchesSearch = name.includes(searchTerm);
            let matchesPrice = price >= minPrice && price <= maxPrice;
            let matchesCategory = categories.length === 0 || categories.includes(category);
            
            if (matchesSearch && matchesPrice && matchesCategory) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });

        sortProducts(); 
    } catch (e) {
        // Muestra el error en la consola
        console.error("Error crítico durante el filtrado:", e);
    }
} // <--- CIERRE CORRECTO DE filterProducts


function sortProducts() {
    const sortValue = document.getElementById('ordenar').value;
    const productList = document.getElementById('product-list');
    
    // Obtener solo los productos visibles
    let visibleProducts = Array.from(productList.querySelectorAll('.card-item:not([style*="display: none"])'));

    visibleProducts.sort((a, b) => {
        // Usamos || 0 para manejar cualquier producto sin data-precio sin que rompa el script
        const priceA = parseFloat(a.dataset.precio || 0);
        const priceB = parseFloat(b.dataset.precio || 0);

        if (sortValue === 'asc') {
            return priceA - priceB;
        } else if (sortValue === 'desc') {
            return priceB - priceA;
        }
        return 0; 
    });

    // Reinsertar los productos ordenados en el contenedor
    visibleProducts.forEach(product => {
        productList.appendChild(product);
    });
}