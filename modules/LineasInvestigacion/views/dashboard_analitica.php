<!-- modules/LineasInvestigacion/views/dashboard_analitica.php -->
<div class="li-gestor-wrapper animate-fade-in">

    <style>
.ag-header-banner {
    background: linear-gradient(135deg, rgba(80, 89, 132, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%) !important;
    color: #ffffff;
    border-radius: 14px;
    padding: 2.5rem 3rem;
    box-shadow: 0 16px 36px rgba(15, 23, 42, 0.15);
    margin-bottom: 2.5rem;
    position: relative;
    overflow: hidden;
}
.ag-header-banner::before {
    content: '';
    position: absolute;
    top: -50%; right: -10%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
    border-radius: 50%;
}
.ag-header-subtitle {
    display: inline-flex; 
    align-items: center; 
    gap: 0.5rem; 
    color: #94a3b8; 
    font-weight: 800; 
    font-size: 0.8rem; 
    text-transform: uppercase; 
    letter-spacing: 1.5px; 
    margin-bottom: 0.5rem;
}
.ag-header-title {
    font-size: 2.2rem; 
    font-weight: 800; 
    margin: 0 0 0.8rem 0; 
    color: #ffffff;
    letter-spacing: -0.5px;
}
.ag-header-desc {
    margin: 0; 
    color: #cbd5e1; 
    font-size: 1.05rem;
    max-width: 800px;
    line-height: 1.6;
}
</style>

<div class="ag-header-banner">
    <canvas id="li-nodes-canvas-1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.6;"></canvas>
    <div style="position: relative; z-index: 1;">
        <div class="ag-header-subtitle">
            <i class="ph-bold ph-brain"></i> SISTEMA INTEGRAL
        </div>
        <h1 class="ag-header-title">Dashboard Analítico Predictivo (IA)</h1>
        <p class="ag-header-desc">Proyección trimestral de volumen mediante Machine Learning.</p>
    </div>
</div>

        <!-- Controles movidos a la barra de configuración abajo -->
    </div>

    <!-- ╔══ SECCIÓN 1: TENDENCIAS (SERIES TEMPORALES) ══════════════════════╗ -->
    <div class="li-form-card" style="margin-bottom: 2rem;">
        <div class="li-form-header">
            <i class="ph-bold ph-chart-line-up" style="color:var(--li-emerald, #10b981);"></i>
            <span style="color: #2c3e50; font-weight: 600;">Proyección de Tendencias (Adopción y Saturación)</span>
        </div>

        <div class="li-form-body">
            
            <!-- BARRA DE CONTROLES (NUEVA) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px; box-shadow: 0 2px 4px -1px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: #f1f5f9; color: #505984; padding: 10px; border-radius: 8px;">
                        <i class="ph-bold ph-calendar-plus" style="font-size: 1.4rem;"></i>
                    </div>
                    <div>
                        <strong style="color: #121a3e; font-size: 1rem; display: block;">Horizonte de Proyección</strong>
                        <span style="color: #64748b; font-size: 0.85rem;">Define hasta qué fecha la IA calculará la demanda esperada.</span>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <select id="select-trimestres" style="padding: 10px 15px; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; color: #121a3e; font-weight: 600; font-size: 0.9rem; outline: none; cursor: pointer; min-width: 260px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);">
                        <option value="1">1 Trimestre (Inmediato)</option>
                        <option value="2">2 Trimestres</option>
                        <option value="3">3 Trimestres (1 Año Académico)</option>
                        <option value="4" selected>4 Trimestres</option>
                        <option value="6">6 Trimestres (2 Años Académicos)</option>
                        <option value="8">8 Trimestres</option>
                        <option value="9">9 Trimestres (3 Años Académicos)</option>
                        <option value="12">12 Trimestres (4 Años Académicos)</option>
                    </select>
                    <button id="btn-proyectar" style="background: #505984; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(80, 89, 132, 0.3); transition: all 0.2s ease; transition: all 0.2s ease;" onmouseover="this.style.background=\'#3C456A\'; this.style.transform=\'translateY(-2px)\'" onmouseout="this.style.background=\'#505984\'; this.style.transform=\'translateY(0)\'">
                        <i class="ph-bold ph-magic-wand" style="font-size: 1.1rem;"></i> Actualizar Modelo
                    </button>
                </div>
            </div>

            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">
                El motor de Inteligencia Artificial analiza el historial real de proyectos registrados en la plataforma. Mediante algoritmos de Regresión Lineal, proyecta el volumen de demanda para próximos trimestres académicos, facilitando la toma de decisiones estratégicas.
            </p>
            
            <div id="loading-tendencias" style="display: none; color: #505984; font-style: italic; margin-bottom: 1rem;">
                <i class="ph-bold ph-spinner ph-spin"></i> Calculando proyecciones con IA...
            </div>
            
            <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 2rem; align-items: start;">
                
                <!-- Columna Izquierda: Gráfico + Insights -->
                <div>
                    <!-- Gráfico de Adopción -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <canvas id="tendenciasChart" height="280"></canvas>
                    </div>

                    <!-- Insights Automáticos (Se llenará con JS) -->
                    <div id="insights-tendencias" style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    </div>
                </div>
                
                <!-- Panel de KPIs y Saturación -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h4 style="margin-bottom: 1rem; color: #1e293b; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
                        <i class="ph-fill ph-gauge"></i> Métricas de Error y Saturación
                    </h4>
                    
                    <div id="metricas-tendencias-resultado" style="font-size: 0.9rem; color: #475569;">
                        <div class="li-empty-state" style="padding: 1rem;">
                            <i class="ph-bold ph-chart-bar" style="font-size: 2rem; color: #cbd5e1;"></i>
                            <p style="font-size: 0.8rem; margin-top: 0.5rem; color: #94a3b8;">Presiona el botón superior para procesar el tensor histórico.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<!-- Cargar Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Cargar el script que maneja la UI analítica -->
<script src="../modules/LineasInvestigacion/assets/analitica_ui.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('li-nodes-canvas-1');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.offsetWidth;
        let height = canvas.height = canvas.offsetHeight;
        
        window.addEventListener('resize', () => {
            if (!canvas) return;
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        });

        const particles = [];
        const numParticles = 40;

        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.2
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);

            for (let i = 0; i < numParticles; i++) {
                const p = particles[i];
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(112, 144, 203, 0.75)';
                ctx.fill();

                for (let j = i + 1; j < numParticles; j++) {
                    const p2 = particles[j];
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(112, 144, 203, ${0.4 - dist/250})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }
});
</script>
