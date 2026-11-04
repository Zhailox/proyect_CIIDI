document.addEventListener('DOMContentLoaded', async () => {
    const boxCat = document.getElementById('box-categorias');
    const boxTag = document.getElementById('box-etiquetas'); // Asegúrate de que el div de etiquetas tenga este ID
    
    // Capturamos las variables globales definidas en la vista (vacías si es un artículo nuevo)
    const catSeleccionadas = window.CAT_SELECCIONADAS || [];
    const tagSeleccionadas = window.TAG_SELECCIONADAS || [];

    let catalogos = localStorage.getItem('catalogos_articulos');
    
    // Si el caché no existe o expiró (24h = 86400000ms), consultamos la API
    if (!catalogos || (Date.now() - JSON.parse(catalogos).timestamp > 86400000)) {
        try {
            const res = await fetch('?ruta=api-catalogos-art');
            const data = await res.json();
            localStorage.setItem('catalogos_articulos', JSON.stringify({
                timestamp: Date.now(), data: data
            }));
            catalogos = data;
        } catch (error) {
            console.error("Error cargando catálogos:", error);
            return;
        }
    } else {
        catalogos = JSON.parse(catalogos).data;
    }

    // Renderizamos las Categorías
    if (boxCat && catalogos.categorias) {
        boxCat.innerHTML = catalogos.categorias.map(cat => `
            <label class="checkbox-label">
                <input type="checkbox" name="categorias[]" value="${cat.id}" ${catSeleccionadas.includes(parseInt(cat.id)) ? 'checked' : ''}>
                ${cat.nombre}
            </label>
        `).join('');
    }

    // Renderizamos las Etiquetas
    if (boxTag && catalogos.etiquetas) {
        boxTag.innerHTML = catalogos.etiquetas.map(tag => `
            <label class="checkbox-label">
                <input type="checkbox" name="etiquetas[]" value="${tag.id}" ${tagSeleccionadas.includes(parseInt(tag.id)) ? 'checked' : ''}>
                ${tag.nombre}
            </label>
        `).join('');
    }
});