<div class="fc-read-container">
    
    <div class="fc-breadcrumb">
        <a href="foro-general">⬅ Volver al Foro Comunitario</a> / Lectura de Hilo
    </div>

    <article class="fc-op-card">
        <h1 class="fc-op-title">Error al generar PDF con la librería FPDF usando puro PHP</h1>
        
        <div class="fc-op-meta">
            <div class="fc-op-author">
                Posteado por <strong>DevTrujillo</strong> • 08 de Junio, 2026
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <span class="fc-tag">PHP</span>
                <span class="fc-tag">Bugs</span>
            </div>
        </div>

        <div class="fc-op-body">
            <p>Buenas tardes a toda la comunidad.</p>
            <br>
            <p>Estoy intentando generar los reportes de inventario de mi PST usando FPDF. El código conecta bien a la base de datos relacional, pero al momento de renderizar la tabla y llamar a <code>$pdf->Output();</code>, el navegador me lanza un error que dice: <strong>"FPDF error: Some data has already been output, can't send PDF file"</strong>.</p>
            <br>
            <p>Ya revisé que no haya espacios en blanco antes de la etiqueta <code>&lt;?php</code>. ¿A alguien más le ha pasado trabajando con esta arquitectura de microkernel sin frameworks?</p>
        </div>

        <div class="fc-op-actions">
            <button class="fc-btn-ghost">▲ Votar (28)</button>
        </div>
    </article>

    <div class="fc-timeline">

        <div class="fc-timeline-item">
            <div class="fc-timeline-avatar">A</div>
            <div class="fc-comment-box">
                <div class="fc-comment-header">
                    <span class="fc-comment-author">AnaP_Trayecto4</span>
                    <span class="fc-comment-date">Hace 2 horas</span>
                </div>
                <div class="fc-comment-body">
                    <p>¡Hola! Ese error suele pasar si en algún archivo anterior (como tu index.php o el kernel) tienes un <code>echo</code> o incluso un salto de línea fuera de las etiquetas de PHP. Revisa el archivo del controlador donde llamas al modelo, a veces se escapa un espacio al final del archivo.</p>
                </div>
            </div>
        </div>

        <div class="fc-timeline-item tutor">
            <div class="fc-timeline-avatar">J</div>
            <div class="fc-comment-box">
                <div class="fc-comment-header">
                    <span class="fc-comment-author">Ing. Josué García <span class="fc-badge-tutor">Tutor Académico</span></span>
                    <span class="fc-comment-date">Hace 30 minutos</span>
                </div>
                <div class="fc-comment-body">
                    <p>Concuerdo con Ana. Adicionalmente, te recomiendo colocar <code>ob_start();</code> al inicio de tu controlador principal para limpiar el búfer de salida en la cabecera antes de generar el PDF con los headers nativos.</p>
                    <br>
                    <p>En la documentación del repositorio central (Módulo 2) dejamos un ejemplo de esta arquitectura.</p>
                </div>
            </div>
        </div>

        <div class="fc-timeline-item">
            <div class="fc-timeline-avatar" style="background: var(--color-principal);">Tú</div>
            <div class="fc-reply-box">
                <h4>Agregar una respuesta</h4>
                <form action="#" onsubmit="event.preventDefault();">
                    <textarea class="fc-textarea" style="border-style: solid; margin-bottom: 1rem;" placeholder="Escribe tu aporte o solución a este problema..." required></textarea>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="fc-btn-primary">Publicar Respuesta</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>