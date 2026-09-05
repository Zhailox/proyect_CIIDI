document.addEventListener('DOMContentLoaded', () => {
    const dropzone = document.getElementById('dropzone-portada');
    const fileInput = document.getElementById('input_imagen_portada');
    const previewName = document.getElementById('preview-image-name');

    if (!dropzone || !fileInput) return;

    // Leemos los límites inyectados desde PHP
    const maxMb = parseInt(dropzone.getAttribute('data-max-mb')) || 5;
    const maxBytes = maxMb * 1024 * 1024;
    const isServerLimit = dropzone.getAttribute('data-server-limit') === 'true';
    
    const extsStr = dropzone.getAttribute('data-exts') || '';
    const allowedExts = extsStr.split(',').map(e => e.trim().toLowerCase());

    function mostrarModalError(titulo, mensaje) {
        const overlay = document.createElement('div');
        overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,34,68,0.8); z-index:99999; display:flex; align-items:center; justify-content:center; backdrop-filter:blur(4px);';
        overlay.innerHTML = `
            <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <i class="ph-bold ph-warning-circle" style="font-size: 3.5rem; color: #dc2626; margin-bottom: 1rem; display: block;"></i>
                <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size:1.2rem;">${titulo}</h3>
                <p style="color: #475569; font-size: 0.9rem; margin-bottom: 1.5rem; line-height:1.5;">${mensaje}</p>
                <button type="button" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.6rem;" onclick="this.closest('div').parentElement.remove()">Entendido</button>
            </div>
        `;
        document.body.appendChild(overlay);
    }

    function validarArchivo(file) {
        if (!file) return false;
        
        const fileName = file.name.toLowerCase();
        const isValidExt = allowedExts.some(ext => fileName.endsWith(ext));
        
        if (!isValidExt) {
            mostrarModalError('Formato Inválido', `El archivo <strong>${file.name}</strong> no es admitido.<br><br>Los formatos válidos son: <b>${extsStr}</b>`);
            return false;
        }

        if (file.size > maxBytes) {
            const actualMb = (file.size / (1024 * 1024)).toFixed(2);
            let motivo = isServerLimit 
                ? `El límite actual de <b>${maxMb} MB</b> está siendo forzado por la configuración técnica de tu servidor (php.ini).` 
                : `El límite configurado en el sistema es de <b>${maxMb} MB</b>.`;

            mostrarModalError('Archivo muy pesado', `La imagen que intentas subir pesa <strong>${actualMb} MB</strong>.<br><br>${motivo}`);
            return false;
        }
        return true;
    }

    dropzone.addEventListener('click', () => fileInput.click());

    ['dragenter', 'dragover'].forEach(evt => dropzone.addEventListener(evt, e => {
        e.preventDefault();
        dropzone.style.borderColor = 'var(--color-terciario)';
        dropzone.style.backgroundColor = '#eff6ff';
    }));

    ['dragleave', 'drop'].forEach(evt => dropzone.addEventListener(evt, e => {
        e.preventDefault();
        dropzone.style.borderColor = 'rgba(0,0,0,0.2)';
        dropzone.style.backgroundColor = '#f8fafc';
    }));

    dropzone.addEventListener('drop', e => {
        if (e.dataTransfer.files.length) {
            const file = e.dataTransfer.files[0];
            if (validarArchivo(file)) {
                fileInput.files = e.dataTransfer.files;
                actualizarVistaPrevia();
            }
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (!validarArchivo(file)) {
                fileInput.value = ''; 
                if (previewName) previewName.style.display = 'none';
            } else {
                actualizarVistaPrevia();
            }
        }
    });

    function actualizarVistaPrevia() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (previewName) {
                previewName.innerHTML = `<i class="ph-bold ph-check-circle" style="color:#16a34a;"></i> <strong>${file.name}</strong>`;
                previewName.style.display = 'block';
            }
        }
    }
});