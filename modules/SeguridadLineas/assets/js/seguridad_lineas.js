/* ==========================================================================
   SEGURIDAD · LÍNEAS DE INVESTIGACIÓN — Cliente del Centro de Operaciones
   Sin dependencias. Toda la configuración llega en window.SEG_LINEAS (ver views/_pie.php).
   ========================================================================== */
(function () {
    'use strict';

    const root = document.querySelector('.sec-lineas[data-sl-page]');
    if (!root) return;

    const CFG    = window.SEG_LINEAS || {};
    const PUEDE  = CFG.puede || {};
    const PAGINA = root.getAttribute('data-sl-page');

    const $  = (s, c) => (c || root).querySelector(s);
    const $$ = (s, c) => Array.from((c || root).querySelectorAll(s));
    const esc = (v) => String(v ?? '').replace(/[&<>"']/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));
    const RUTA = {
        feed: 'seguridad-lineas-api-feed',
        evento: 'seguridad-lineas-api-evento',
        simular: 'seguridad-lineas-simular',
        verificar: 'seguridad-lineas-verificar',
        guardarMatriz: 'seguridad-lineas-guardar-matriz',
        restablecerMatriz: 'seguridad-lineas-restablecer-matriz',
        buscarUsuario: 'seguridad-lineas-buscar-usuario',
        excepcionGuardar: 'seguridad-lineas-excepcion-guardar',
        excepcionEliminar: 'seguridad-lineas-excepcion-eliminar',
        rutaGuardar: 'seguridad-lineas-ruta-guardar',
        bloquearIp: 'seguridad-lineas-bloquear-ip',
        desbloquearIp: 'seguridad-lineas-desbloquear-ip',
        alertaAtender: 'seguridad-lineas-alerta-atender',
        expulsar: 'seguridad-lineas-expulsar',
        politicasGuardar: 'seguridad-lineas-politicas-guardar',
        purgar: 'seguridad-lineas-purgar',
    };

    /* ─────────────────────────────── Utilidades ─────────────────────────────── */

    function toast(mensaje, error) {
        const cont = $('#slToasts');
        if (!cont) return alert(mensaje);
        const t = document.createElement('div');
        t.className = 'sl-toast' + (error ? ' is-error' : '');
        t.innerHTML = '<i class="ph ' + (error ? 'ph-x-circle' : 'ph-check-circle') + '"></i><div>' + esc(mensaje) + '</div>';
        cont.appendChild(t);
        setTimeout(() => { t.classList.add('is-out'); setTimeout(() => t.remove(), 320); }, error ? 6500 : 4000);
    }

    async function api(ruta, datos, params) {
        let url = ruta;
        if (params) {
            const qs = new URLSearchParams();
            Object.keys(params).forEach((k) => { if (params[k] !== undefined && params[k] !== null) qs.append(k, params[k]); });
            url += (url.includes('?') ? '&' : '?') + qs.toString();
        }
        const opts = {
            method: datos ? 'POST' : 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        };
        if (datos) {
            const fd = datos instanceof FormData ? datos : new FormData();
            if (!(datos instanceof FormData)) Object.keys(datos).forEach((k) => fd.append(k, datos[k]));
            fd.set('csrf_token', CFG.csrf || '');
            opts.headers['X-CSRF-Token'] = CFG.csrf || '';
            opts.body = fd;
        }
        const res = await fetch(url, opts);
        let json = null;
        try { json = await res.json(); } catch (e) { /* respuesta no JSON */ }
        if (!json) throw new Error('Respuesta inválida del servidor (HTTP ' + res.status + ').');
        if (!res.ok || json.status === 'error' || json.success === false) {
            throw new Error((json.incidente ? '[' + json.incidente + '] ' : '') + (json.message || 'Error HTTP ' + res.status));
        }
        return json;
    }

    function cargando(btn, on) {
        if (!btn) return;
        btn.classList.toggle('is-loading', !!on);
        btn.disabled = !!on;
    }

    function recargar(retardo) {
        setTimeout(() => window.location.reload(), retardo ?? 700);
    }

    /* ───────────────────────────────── Modal ───────────────────────────────── */

    const modal = $('#slModal');
    let modalResolver = null;

    function abrirModal(opc) {
        if (!modal) return Promise.resolve(null);
        $('#slModalTitle', modal).textContent = '> ' + (opc.titulo || 'sistema');
        $('#slModalBody', modal).innerHTML = opc.html || '';
        const foot = $('#slModalFoot', modal);
        foot.innerHTML = '';
        (opc.botones || [{ texto: 'Cerrar', valor: null, clase: 'sl-btn-ghost' }]).forEach((b) => {
            const el = document.createElement('button');
            el.type = 'button';
            el.className = 'sl-btn ' + (b.clase || '');
            el.innerHTML = (b.icono ? '<i class="ph ' + b.icono + '"></i>' : '') + esc(b.texto);
            el.addEventListener('click', () => cerrarModal(b.valor));
            foot.appendChild(el);
        });
        $('.sl-modal-box', modal).classList.toggle('is-danger', !!opc.peligro);
        modal.hidden = false;
        root.classList.add('sl-modal-open');
        const primerInput = $('input, textarea, select', $('#slModalBody', modal));
        setTimeout(() => (primerInput || foot.lastElementChild)?.focus(), 30);
        return new Promise((resolve) => { modalResolver = resolve; });
    }

    function cerrarModal(valor) {
        if (!modal || modal.hidden) return;
        modal.hidden = true;
        root.classList.remove('sl-modal-open');
        const r = modalResolver;
        modalResolver = null;
        if (r) r(valor === undefined ? null : valor);
    }

    function confirmar(titulo, html, textoOk, peligro) {
        return abrirModal({
            titulo, html, peligro: peligro !== false,
            botones: [
                { texto: 'Cancelar', valor: false, clase: 'sl-btn-ghost' },
                { texto: textoOk || 'Confirmar', valor: true, clase: peligro === false ? '' : 'sl-btn-danger', icono: 'ph-check' },
            ],
        }).then((v) => v === true);
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.closest('[data-sl-close]')) cerrarModal(null);
        });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') cerrarModal(null); });
    }

    /* ─────────────────────────────── Efectos ─────────────────────────────── */

    function lluviaMatrix() {
        const canvas = $('#slRain');
        if (!canvas || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const ctx = canvas.getContext('2d');
        const chars = 'アカサタナハマヤラワ01ABCDEF<>/{}[]#$%&*+=SECLINEAS'.split('');
        const tam = 14;
        let cols = [], w = 0, h = 0;

        function ajustar() {
            w = canvas.width = canvas.offsetWidth;
            h = canvas.height = canvas.offsetHeight;
            cols = Array(Math.ceil(w / tam)).fill(0).map(() => Math.random() * -h / tam);
        }
        ajustar();
        window.addEventListener('resize', ajustar);

        let ultimo = 0;
        function dibujar(t) {
            requestAnimationFrame(dibujar);
            if (t - ultimo < 55 || document.hidden) return;
            ultimo = t;
            ctx.fillStyle = 'rgba(3, 6, 8, 0.18)';
            ctx.fillRect(0, 0, w, h);
            ctx.font = tam + 'px JetBrains Mono, monospace';
            cols.forEach((y, i) => {
                const c = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillStyle = Math.random() > 0.975 ? '#eafff4' : '#00ff9c';
                ctx.fillText(c, i * tam, y * tam);
                cols[i] = (y * tam > h && Math.random() > 0.97) ? 0 : y + 1;
            });
        }
        requestAnimationFrame(dibujar);
    }

    function tipeo() {
        $$('[data-sl-type]').forEach((el) => {
            const texto = el.getAttribute('data-sl-type') || '';
            let i = 0;
            (function paso() {
                el.textContent = texto.slice(0, i++);
                if (i <= texto.length) setTimeout(paso, 35 + Math.random() * 60);
            })();
        });
    }

    function reloj() {
        const el = $('#slClock');
        if (!el) return;
        const tick = () => {
            const d = new Date();
            el.textContent = d.toLocaleTimeString('es-VE', { hour12: false }) + ' VET';
        };
        tick();
        setInterval(tick, 1000);
    }

    /* ────────────────────────── Render compartido ────────────────────────── */

    function lineaEvento(ev) {
        const div = document.createElement('div');
        div.className = 'sl-line res-' + String(ev.resultado).toLowerCase() + ' sev-' + String(ev.severidad).toLowerCase();
        div.dataset.id = ev.id;
        div.title = ev.detalles || '';
        div.innerHTML =
            '<span class="t">[' + esc(String(ev.fecha).slice(11, 19)) + ']</span>' +
            '<span class="r">' + esc(ev.resultado) + '</span>' +
            '<span class="u">' + esc(ev.usuario_nombre || 'anon') + '</span>' +
            '<span class="ip">' + esc(ev.ip) + '</span>' +
            '<span class="p">' + esc(ev.metodo) + ' /' + esc(ev.ruta || ev.accion) + ' · ' + esc(ev.submodulo) + ':' + esc(ev.accion) + '</span>';
        return div;
    }

    function badge(clase, texto) {
        return '<span class="sl-badge ' + clase + '">' + esc(texto) + '</span>';
    }

    async function detalleEvento(id) {
        try {
            const r = await api(RUTA.evento, null, { id });
            const e = r.evento;
            const nivel = Number(e.nivel) === 999 ? 'VISITANTE' : 'N' + e.nivel;
            const html =
                '<dl class="sl-dl">' +
                '<dt>Evento</dt><dd>#' + esc(e.id) + ' · ' + esc(e.fecha) + '</dd>' +
                '<dt>Severidad</dt><dd>' + badge('sl-sev-' + String(e.severidad).toLowerCase(), e.severidad) + ' ' + badge('sl-res-' + String(e.resultado).toLowerCase(), e.resultado) + '</dd>' +
                '<dt>Submódulo</dt><dd>' + esc(e.submodulo_titulo) + ' <span class="sl-help">(' + esc(e.submodulo) + ')</span></dd>' +
                '<dt>Acción</dt><dd>' + esc(e.accion) + '</dd>' +
                '<dt>Ruta</dt><dd><code>' + esc(e.metodo) + ' /' + esc(e.ruta) + '</code></dd>' +
                '<dt>Actor</dt><dd>' + esc(e.usuario_nombre || 'anónimo') + (e.usuario_id ? ' <span class="sl-help">ID ' + esc(e.usuario_id) + '</span>' : '') + ' · ' + esc(nivel) + '</dd>' +
                '<dt>IP</dt><dd>' + esc(e.ip) + '</dd>' +
                '<dt>User-Agent</dt><dd class="sl-help">' + esc(e.user_agent || '—') + '</dd>' +
                '<dt>Detalles</dt><dd>' + esc(e.detalles || '—') + '</dd>' +
                '<dt>Hash anterior</dt><dd class="sl-hash">' + esc(e.hash_anterior) + '</dd>' +
                '<dt>Hash</dt><dd class="sl-hash">' + esc(e.hash) + '</dd>' +
                '<dt>Firma HMAC</dt><dd>' + (e.firma_valida ? badge('sl-res-permitido', '✓ VÁLIDA') : badge('sl-res-denegado', '✕ ALTERADA')) + '</dd>' +
                '</dl>';
            const botones = [{ texto: 'Eventos de esta IP', valor: 'ip', clase: 'sl-btn-cyan', icono: 'ph-funnel' }];
            if (PUEDE.configurar && e.ip && e.ip !== CFG.ip) botones.push({ texto: 'Bloquear IP', valor: 'bloquear', clase: 'sl-btn-danger', icono: 'ph-prohibit' });
            botones.push({ texto: 'Cerrar', valor: null, clase: 'sl-btn-ghost' });
            const v = await abrirModal({ titulo: 'evento forense #' + e.id, html, botones, peligro: !e.firma_valida });
            if (v === 'ip') window.location.href = 'seguridad-lineas-bitacora?ip=' + encodeURIComponent(e.ip);
            if (v === 'bloquear') bloqueoRapido(e.ip);
        } catch (err) {
            toast(err.message, true);
        }
    }

    async function verificarIntegridad(btn) {
        const out = $('#slVerifyOut');
        cargando(btn, true);
        if (out) out.innerHTML = '<div class="sl-alert sl-alert-info"><i class="ph ph-spinner"></i><div>Recalculando la cadena HMAC…</div></div>';
        try {
            const r = await api(RUTA.verificar);
            let html = '<div class="sl-alert ' + (r.integro ? 'sl-alert-info' : 'sl-alert-danger') + '">' +
                '<i class="ph ' + (r.integro ? 'ph-seal-check' : 'ph-seal-warning') + '"></i><div><b>' + esc(r.mensaje) + '</b>' +
                '<div class="sl-help">' + esc(r.total) + ' eventos · ' + esc(r.ms ?? 0) + ' ms</div>';
            if (!r.integro && r.corruptos && r.corruptos.length) {
                html += '<ul class="sl-verify-list">' + r.corruptos.slice(0, 50).map((c) => '<li>#' + esc(c.id) + ' — ' + esc(c.motivo) + '</li>').join('') + '</ul>';
            }
            html += '</div></div>';
            if (out) out.innerHTML = html;
            toast(r.mensaje, !r.integro);
        } catch (err) {
            if (out) out.innerHTML = '';
            toast(err.message, true);
        } finally {
            cargando(btn, false);
        }
    }

    async function bloqueoRapido(ip) {
        const html =
            '<div class="sl-form">' +
            '<p>Se bloqueará el acceso de <b style="color:var(--sl-red)">' + esc(ip) + '</b> a todo el macro módulo.</p>' +
            '<div class="sl-field"><label>Duración</label><select class="sl-select" id="slQbMin">' +
            '<option value="60">1 hora</option><option value="1440">24 horas</option><option value="10080">7 días</option><option value="0">Permanente</option></select></div>' +
            '<div class="sl-field"><label>Motivo</label><input class="sl-input" id="slQbMotivo" value="Bloqueo manual desde el Centro de Operaciones"></div></div>';
        const ok = await confirmar('bloquear ' + ip, html, 'Bloquear', true);
        if (!ok) return;
        try {
            const r = await api(RUTA.bloquearIp, { ip, minutos: $('#slQbMin', modal)?.value || 60, motivo: $('#slQbMotivo', modal)?.value || 'Bloqueo manual' });
            toast(r.message);
            recargar();
        } catch (err) {
            toast(err.message, true);
        }
    }

    /* ─────────────────────────── Acciones globales ─────────────────────────── */

    root.addEventListener('click', async (e) => {
        const t = e.target;

        const linea = t.closest('.sl-line[data-id]');
        if (linea) return detalleEvento(linea.dataset.id);

        const fila = t.closest('tr[data-sl-evento]');
        if (fila && !t.closest('a, button')) return detalleEvento(fila.dataset.slEvento);

        const ver = t.closest('[data-sl-verify]');
        if (ver) return verificarIntegridad(ver);

        const rap = t.closest('[data-sl-bloquear-rapido]');
        if (rap) return bloqueoRapido(rap.dataset.slBloquearRapido);

        const at = t.closest('[data-sl-atender]');
        if (at) {
            const id = at.dataset.slAtender;
            if (id === 'todas' && !(await confirmar('atender alertas', '<p>¿Marcar <b>todas</b> las alertas abiertas como atendidas?</p>', 'Atender todas', false))) return;
            cargando(at, true);
            try {
                const r = await api(RUTA.alertaAtender, { id });
                toast(r.message);
                const item = at.closest('[data-alerta]');
                if (item && id !== 'todas') {
                    item.remove();
                    const kv = $('[data-sl-kpi="alertas_abiertas"]');
                    if (kv) kv.textContent = Math.max(0, Number(kv.textContent) - 1);
                } else {
                    recargar(400);
                }
            } catch (err) {
                toast(err.message, true);
                cargando(at, false);
            }
            return;
        }

        const exp = t.closest('[data-sl-expulsar]');
        if (exp) {
            const nombre = exp.dataset.nombre || 'el usuario';
            if (!(await confirmar('expulsar sesión', '<p>Se revocará la sesión de <b>' + esc(nombre) + '</b>. Será desconectado en su próxima petición.</p>', 'Expulsar'))) return;
            cargando(exp, true);
            try {
                const r = await api(RUTA.expulsar, { usuario_id: exp.dataset.slExpulsar });
                toast(r.message);
                exp.closest('tr')?.classList.add('is-muted');
                exp.remove();
            } catch (err) {
                toast(err.message, true);
                cargando(exp, false);
            }
        }
    });

    /* ─────────────────────────────── SOC ─────────────────────────────── */

    const NIVELES_AMENAZA = {
        BAJO: ['var(--sl-green)', 'Actividad normal. Sin patrones hostiles relevantes.'],
        MODERADO: ['var(--sl-amber)', 'Se observan denegaciones o eventos sensibles. Mantener vigilancia.'],
        ELEVADO: ['#ff7a45', 'Ráfagas de denegaciones o alertas abiertas. Revisar la pestaña Amenazas.'],
        CRITICO: ['var(--sl-red)', 'Múltiples indicadores hostiles. Considera activar Lockdown.'],
    };

    function dibujarGrafica(serie) {
        const svg = $('#slChart');
        if (!svg || !Array.isArray(serie)) return;
        const W = 720, H = 220, L = 34, R = 8, T = 10, B = 24;
        const max = Math.max(4, ...serie.map((s) => s.permitidos + s.denegados + s.sistema));
        const paso = (W - L - R) / serie.length;
        const ancho = Math.max(4, paso * 0.62);
        const y = (v) => (H - B) - (v / max) * (H - B - T);
        let g = '<defs>' +
            '<linearGradient id="slGradOk" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#00ff9c"/><stop offset="1" stop-color="#00ff9c" stop-opacity=".25"/></linearGradient>' +
            '<linearGradient id="slGradKo" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff3860"/><stop offset="1" stop-color="#ff3860" stop-opacity=".35"/></linearGradient>' +
            '</defs>';
        for (let i = 0; i <= 4; i++) {
            const v = Math.round(max * i / 4);
            const yy = y(v);
            g += '<line class="sl-axis" x1="' + L + '" x2="' + (W - R) + '" y1="' + yy + '" y2="' + yy + '"/>';
            g += '<text x="' + (L - 6) + '" y="' + (yy + 3) + '" text-anchor="end">' + v + '</text>';
        }
        serie.forEach((s, i) => {
            const x = L + i * paso + (paso - ancho) / 2;
            let base = H - B;
            [['permitidos', 'sl-bar-ok'], ['denegados', 'sl-bar-ko'], ['sistema', 'sl-bar-sys']].forEach(([k, cls]) => {
                const v = s[k];
                if (!v) return;
                const h = (v / max) * (H - B - T);
                base -= h;
                g += '<rect class="' + cls + '" x="' + x.toFixed(1) + '" y="' + base.toFixed(1) + '" width="' + ancho.toFixed(1) + '" height="' + Math.max(1, h).toFixed(1) + '" rx="2"><title>' + esc(s.hora) + ' · ' + k + ': ' + v + '</title></rect>';
            });
            if (i % 3 === 0 || i === serie.length - 1) {
                g += '<text x="' + (x + ancho / 2).toFixed(1) + '" y="' + (H - 6) + '" text-anchor="middle">' + esc(s.hora) + '</text>';
            }
        });
        svg.innerHTML = g;
    }

    function actualizarKpis(k) {
        if (!k) return;
        Object.keys(k).forEach((clave) => {
            $$('[data-sl-kpi="' + clave + '"]').forEach((el) => {
                const nuevo = String(k[clave]);
                if (el.textContent !== nuevo) {
                    el.textContent = nuevo;
                    const caja = el.classList.contains('sl-kpi-value') ? el : el.closest('.sl-kpi-value');
                    if (caja) { caja.classList.remove('is-bump'); void caja.offsetWidth; caja.classList.add('is-bump'); }
                }
            });
        });
        const gauge = $('#slGauge');
        if (gauge && k.amenaza !== undefined) {
            const [color, desc] = NIVELES_AMENAZA[k.amenaza_nivel] || NIVELES_AMENAZA.BAJO;
            gauge.style.setProperty('--v', k.amenaza);
            gauge.style.setProperty('--c', color);
            const n = $('#slGaugeNivel');
            if (n) { n.textContent = k.amenaza_nivel; n.style.color = color; }
            const d = $('#slGaugeDesc');
            if (d) d.textContent = desc;
        }
    }

    function actualizarAlertas(alertas) {
        const cont = $('#slAlertas');
        if (!cont || !Array.isArray(alertas)) return;
        if (!alertas.length) {
            cont.innerHTML = '<div class="sl-empty"><i class="ph ph-shield-check"></i>Sin alertas abiertas.</div>';
            return;
        }
        cont.innerHTML = alertas.map((a) =>
            '<div class="sl-alert-item" data-alerta="' + esc(a.id) + '"><i class="ph ph-warning-octagon"></i><div>' +
            '<b>' + esc(a.titulo) + '</b><small>' + esc(a.detalle) + '</small><small>' + esc(a.fecha) + (a.ip ? ' · ' + esc(a.ip) : '') + '</small></div>' +
            (PUEDE.configurar ? '<button type="button" class="sl-btn sl-btn-sm" data-sl-atender="' + esc(a.id) + '" title="Marcar como atendida"><i class="ph ph-check"></i></button>' : '') +
            '</div>'
        ).join('');
    }

    function initSoc() {
        try {
            const data = JSON.parse($('#slData')?.textContent || '{}');
            dibujarGrafica(data.serie || []);
        } catch (e) { /* sin datos */ }

        const term = $('#slTerminal');
        if (!term) return;
        term.scrollTop = term.scrollHeight;

        let ultimo = Number(term.dataset.ultimo || 0);
        let pausado = false;
        let ciclo = 0;
        let enCurso = false;
        const estado = $('#slFeedEstado');
        const pausa = $('#slFeedPausa');

        pausa?.addEventListener('click', () => {
            pausado = !pausado;
            pausa.innerHTML = pausado ? '<i class="ph ph-play"></i>Reanudar' : '<i class="ph ph-pause"></i>Pausar';
            estado?.classList.toggle('is-off', pausado);
        });

        async function sondear() {
            if (pausado || document.hidden || enCurso) return;
            enCurso = true;
            ciclo++;
            try {
                const completo = ciclo % 6 === 0;
                const r = await api(RUTA.feed, null, completo ? { desde: ultimo, completo: 1 } : { desde: ultimo });
                const abajo = term.scrollHeight - term.scrollTop - term.clientHeight < 40;
                if (r.eventos && r.eventos.length) {
                    term.querySelector('.sl-line:not([data-id])')?.remove();
                    r.eventos.forEach((ev) => {
                        const l = lineaEvento(ev);
                        l.classList.add('is-new');
                        term.appendChild(l);
                        ultimo = Math.max(ultimo, Number(ev.id));
                    });
                    while (term.children.length > 300) term.firstElementChild.remove();
                    if (abajo) term.scrollTop = term.scrollHeight;
                }
                actualizarKpis(r.kpis);
                if (r.serie) dibujarGrafica(r.serie);
                if (r.alertas) actualizarAlertas(r.alertas);
                const h = $('#slFeedHora');
                if (h) h.textContent = 'sync ' + String(r.hora || '').slice(11, 19);
                estado?.classList.remove('is-danger');
            } catch (err) {
                estado?.classList.add('is-danger');
            } finally {
                enCurso = false;
            }
        }
        setInterval(sondear, Math.max(2, Number(CFG.refresco) || 5) * 1000);
    }

    /* ─────────────────────────────── Permisos ─────────────────────────────── */

    function initSubtabs() {
        const tabs = $$('[data-sl-subtab]');
        if (!tabs.length) return;
        function activar(nombre) {
            if (!$('[data-sl-panel="' + nombre + '"]')) nombre = tabs[0].dataset.slSubtab;
            tabs.forEach((t) => t.classList.toggle('is-active', t.dataset.slSubtab === nombre));
            $$('[data-sl-panel]').forEach((p) => p.classList.toggle('is-active', p.dataset.slPanel === nombre));
        }
        tabs.forEach((t) => t.addEventListener('click', () => {
            activar(t.dataset.slSubtab);
            history.replaceState(null, '', '#' + t.dataset.slSubtab);
        }));
        if (location.hash) activar(location.hash.slice(1));
    }

    function initMatriz() {
        const cont = $('#slMatriz');
        if (!cont) return;
        const bar = $('#slSavebar');

        const editables = () => $$('.sl-cellbtn:not(:disabled)', cont);
        const sucias = () => editables().filter((b) => b.dataset.on !== b.dataset.orig);

        function fijar(btn, on) {
            btn.dataset.on = on ? '1' : '0';
            btn.classList.toggle('is-on', on);
            btn.classList.toggle('is-dirty', btn.dataset.on !== btn.dataset.orig);
        }
        function refrescarBarra() {
            const n = sucias().length;
            if (!bar) return;
            bar.classList.toggle('sl-hidden', n === 0);
            $('#slDirtyCount').textContent = n;
        }
        function alternarGrupo(botones) {
            botones = botones.filter((b) => !b.disabled);
            if (!botones.length) return;
            const algunoOff = botones.some((b) => b.dataset.on !== '1');
            botones.forEach((b) => fijar(b, algunoOff));
            refrescarBarra();
        }

        cont.addEventListener('click', (e) => {
            const btn = e.target.closest('.sl-cellbtn');
            if (btn && !btn.disabled) {
                fijar(btn, btn.dataset.on !== '1');
                return refrescarBarra();
            }
            const col = e.target.closest('th.sl-col');
            if (col) {
                const bloque = col.closest('.sl-matrix-block');
                return alternarGrupo($$('.sl-cellbtn[data-acc="' + col.dataset.acc + '"]', bloque));
            }
            const lvl = e.target.closest('td.sl-lvl');
            if (lvl) return alternarGrupo($$('.sl-cellbtn', lvl.closest('tr')));
        });

        $('[data-sl-descartar]')?.addEventListener('click', () => {
            sucias().forEach((b) => fijar(b, b.dataset.orig === '1'));
            refrescarBarra();
        });

        $('[data-sl-guardar-matriz]')?.addEventListener('click', async (e) => {
            const btn = e.currentTarget;
            const celdas = sucias().map((b) => ({ nivel: Number(b.dataset.nivel), submodulo: b.dataset.sub, accion: b.dataset.acc, permitido: b.dataset.on === '1' }));
            if (!celdas.length) return;
            const restric = celdas.filter((c) => !c.permitido).length;
            const ok = await confirmar('aplicar matriz',
                '<p>Se aplicarán <b>' + celdas.length + '</b> cambio(s), de los cuales <b style="color:var(--sl-red)">' + restric + '</b> retiran permisos.</p><p class="sl-help">El cambio es inmediato para todos los usuarios y queda registrado como evento CRÍTICO.</p>',
                'Aplicar', restric > 0);
            if (!ok) return;
            cargando(btn, true);
            try {
                const r = await api(RUTA.guardarMatriz, { matriz: JSON.stringify(celdas) });
                toast(r.message);
                editables().forEach((b) => { b.dataset.orig = b.dataset.on; b.classList.remove('is-dirty', 'is-default'); });
                refrescarBarra();
                cargando(btn, false);
            } catch (err) {
                toast(err.message, true);
                cargando(btn, false);
            }
        });

        $('[data-sl-restablecer]')?.addEventListener('click', async (e) => {
            const ok = await confirmar('restablecer matriz', '<p>Se eliminarán <b>todas</b> las reglas explícitas y la matriz volverá a los valores por defecto (negocio abierto, Seguridad solo nivel 0).</p>', 'Restablecer');
            if (!ok) return;
            cargando(e.currentTarget, true);
            try {
                const r = await api(RUTA.restablecerMatriz, {});
                toast(r.message);
                recargar();
            } catch (err) {
                toast(err.message, true);
                cargando(e.currentTarget, false);
            }
        });

        window.addEventListener('beforeunload', (ev) => {
            if (sucias().length) { ev.preventDefault(); ev.returnValue = ''; }
        });
    }

    function initAutocompletes() {
        $$('[data-sl-autocomplete]').forEach((box) => {
            const q = $('[data-sl-user-q]', box);
            const hid = $('[data-sl-user-id]', box);
            const sug = $('[data-sl-suggest]', box);
            const picked = $('[data-sl-picked]', box);
            let timer = null, foco = -1, items = [];

            function cerrar() { sug.classList.add('sl-hidden'); foco = -1; }
            function elegir(u) {
                hid.value = u.id;
                picked.innerHTML = '<span><b>' + esc(u.nombre_completo) + '</b><br><small class="sl-help">C.I. ' + esc(u.cedula) + ' · ' + esc(u.rol) + ' · N' + esc(u.nivel_privilegio) + '</small></span>' +
                    '<button type="button" class="sl-btn sl-btn-ghost sl-btn-sm" data-sl-unpick><i class="ph ph-x"></i></button>';
                picked.classList.remove('sl-hidden');
                q.classList.add('sl-hidden');
                cerrar();
                const nivelSel = box.closest('form')?.querySelector('select[name="nivel"]');
                if (nivelSel) nivelSel.disabled = true;
            }
            picked.addEventListener('click', (e) => {
                if (!e.target.closest('[data-sl-unpick]')) return;
                hid.value = '';
                picked.classList.add('sl-hidden');
                q.classList.remove('sl-hidden');
                q.value = '';
                q.focus();
                const nivelSel = box.closest('form')?.querySelector('select[name="nivel"]');
                if (nivelSel) nivelSel.disabled = false;
            });
            q.addEventListener('input', () => {
                clearTimeout(timer);
                const v = q.value.trim();
                if (v.length < 2) return cerrar();
                timer = setTimeout(async () => {
                    try {
                        const r = await api(RUTA.buscarUsuario, null, { q: v });
                        items = r.usuarios || [];
                        sug.innerHTML = items.length
                            ? items.map((u, i) => '<button type="button" data-i="' + i + '">' + esc(u.nombre_completo) + '<small>C.I. ' + esc(u.cedula) + ' · ' + esc(u.rol) + ' · N' + esc(u.nivel_privilegio) + '</small></button>').join('')
                            : '<button type="button" disabled>Sin coincidencias</button>';
                        sug.classList.remove('sl-hidden');
                        foco = -1;
                    } catch (err) {
                        toast(err.message, true);
                    }
                }, 250);
            });
            q.addEventListener('keydown', (e) => {
                const btns = $$('button[data-i]', sug);
                if (!btns.length || sug.classList.contains('sl-hidden')) return;
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    foco = (foco + (e.key === 'ArrowDown' ? 1 : -1) + btns.length) % btns.length;
                    btns.forEach((b, i) => b.classList.toggle('is-focus', i === foco));
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (foco >= 0) elegir(items[foco]);
                } else if (e.key === 'Escape') {
                    cerrar();
                }
            });
            sug.addEventListener('mousedown', (e) => {
                const b = e.target.closest('button[data-i]');
                if (b) { e.preventDefault(); elegir(items[Number(b.dataset.i)]); }
            });
            q.addEventListener('blur', () => setTimeout(cerrar, 150));
        });
    }

    function initSelectoresAccion() {
        $$('form').forEach((form) => {
            const sub = $('[data-sl-sub-select]', form);
            const acc = $('[data-sl-acc-select]', form);
            if (!sub || !acc) return;
            const sync = () => {
                const opt = sub.selectedOptions[0];
                const permitidas = opt && opt.dataset.acciones ? opt.dataset.acciones.split(',') : null;
                Array.from(acc.options).forEach((o) => {
                    o.disabled = !!permitidas && o.value !== '*' && !permitidas.includes(o.value);
                });
                if (acc.selectedOptions[0]?.disabled) {
                    const primera = Array.from(acc.options).find((o) => !o.disabled);
                    if (primera) acc.value = primera.value;
                }
            };
            sub.addEventListener('change', sync);
            sync();
        });
    }

    function initExcepciones() {
        const form = $('#slFormExcepcion');
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            if (!fd.get('usuario_id')) return toast('Selecciona un usuario de la lista.', true);
            const deniega = fd.get('efecto') === 'denegar';
            const ok = await confirmar('registrar excepción',
                '<p>Se <b style="color:' + (deniega ? 'var(--sl-red)' : 'var(--sl-green)') + '">' + (deniega ? 'DENEGARÁ' : 'CONCEDERÁ') + '</b> «' + esc(form.accion.selectedOptions[0].text) + '» en «' + esc(form.submodulo.selectedOptions[0].text) + '» al usuario seleccionado.</p>',
                'Registrar', deniega);
            if (!ok) return;
            const btn = form.querySelector('[type="submit"]');
            cargando(btn, true);
            try {
                const r = await api(RUTA.excepcionGuardar, fd);
                toast(r.message);
                recargar();
            } catch (err) {
                toast(err.message, true);
                cargando(btn, false);
            }
        });

        root.addEventListener('click', async (e) => {
            const del = e.target.closest('[data-sl-excepcion-eliminar]');
            if (!del) return;
            if (!(await confirmar('revocar excepción', '<p>¿Revocar la excepción #' + esc(del.dataset.slExcepcionEliminar) + '?</p>', 'Revocar'))) return;
            cargando(del, true);
            try {
                const r = await api(RUTA.excepcionEliminar, { id: del.dataset.slExcepcionEliminar });
                toast(r.message);
                recargar();
            } catch (err) {
                toast(err.message, true);
                cargando(del, false);
            }
        });
    }

    function initSimulador() {
        const form = $('#slFormSimular');
        if (!form) return;
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const out = $('#slSimOut');
            const fd = new FormData(form);
            const params = {};
            fd.forEach((v, k) => { if (v !== '') params[k] = v; });
            if (form.nivel && form.nivel.disabled) delete params.nivel;
            const btn = form.querySelector('[type="submit"]');
            cargando(btn, true);
            try {
                const r = await api(RUTA.simular, null, params);
                const d = r.decision, c = r.contexto;
                const nivel = Number(c.nivel) === 999 ? 'VISITANTE' : 'N' + c.nivel;
                out.innerHTML =
                    '<div class="sl-verdict ' + (d.permitido ? 'is-ok' : 'is-ko') + '">' +
                    '<div class="sl-verdict-head">' + (d.permitido ? '✓ PERMITIDO' : (d.codigo === 'IP_BLOQUEADA' ? '✕ BLOQUEADO' : '✕ DENEGADO')) + '</div>' +
                    '<pre>$ sim --sub ' + esc(params.submodulo) + ' --acc ' + esc(params.accion) + '\n' +
                    '&gt; actor ..... ' + esc(c.nombre) + ' (' + esc(nivel) + ')\n' +
                    '&gt; origen .... ' + esc(c.ip) + '\n' +
                    '&gt; regla ..... ' + esc(d.codigo) + '\n' +
                    '&gt; motivo .... ' + esc(d.motivo) + '</pre></div>';
            } catch (err) {
                toast(err.message, true);
            } finally {
                cargando(btn, false);
            }
        });
    }

    function initRutas() {
        const filtro = $('#slFiltroRutas');
        filtro?.addEventListener('input', () => {
            const v = filtro.value.trim().toLowerCase();
            $$('#slTablaRutas tbody tr').forEach((tr) => {
                tr.style.display = !v || tr.textContent.toLowerCase().includes(v) ? '' : 'none';
            });
        });

        const clases = { manual: 'is-amber', declarada: 'is-cyan', heuristica: 'is-violet', fija: 'is-green' };
        $$('[data-sl-ruta-select]').forEach((sel) => {
            let previo = sel.value;
            sel.addEventListener('change', async () => {
                sel.disabled = true;
                try {
                    const r = await api(RUTA.rutaGuardar, { ruta: sel.dataset.slRutaSelect, accion: sel.value });
                    toast(r.message);
                    const tag = sel.closest('tr').querySelector('[data-sl-origen]');
                    if (tag && r.efectiva) {
                        tag.textContent = r.efectiva.origen;
                        tag.className = 'sl-badge sl-tag ' + (clases[r.efectiva.origen] || '');
                    }
                    previo = sel.value;
                } catch (err) {
                    toast(err.message, true);
                    sel.value = previo;
                } finally {
                    sel.disabled = false;
                }
            });
        });
    }

    /* ─────────────────────────────── Amenazas ─────────────────────────────── */

    function initAmenazas() {
        const form = $('#slFormBloqueo');
        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            const ok = await confirmar('bloquear ip', '<p>Se bloqueará <b style="color:var(--sl-red)">' + esc(fd.get('ip')) + '</b> en todo el macro módulo.</p>', 'Bloquear');
            if (!ok) return;
            const btn = form.querySelector('[type="submit"]');
            cargando(btn, true);
            try {
                const r = await api(RUTA.bloquearIp, fd);
                toast(r.message);
                recargar();
            } catch (err) {
                toast(err.message, true);
                cargando(btn, false);
            }
        });

        root.addEventListener('click', async (e) => {
            const pre = e.target.closest('[data-sl-prefill-ip]');
            if (pre && form) {
                e.preventDefault();
                form.ip.value = pre.dataset.slPrefillIp;
                form.motivo.value = 'Exceso de accesos denegados en las últimas 24 h';
                form.ip.focus();
                return;
            }
            const des = e.target.closest('[data-sl-desbloquear]');
            if (!des) return;
            if (!(await confirmar('levantar bloqueo', '<p>¿Restaurar el acceso de <b>' + esc(des.dataset.ip) + '</b>?</p>', 'Levantar', false))) return;
            cargando(des, true);
            try {
                const r = await api(RUTA.desbloquearIp, { id: des.dataset.slDesbloquear });
                toast(r.message);
                recargar();
            } catch (err) {
                toast(err.message, true);
                cargando(des, false);
            }
        });
    }

    /* ─────────────────────────────── Políticas ─────────────────────────────── */

    function initPoliticas() {
        const form = $('#slFormPoliticas');
        if (form && PUEDE.configurar) {
            const inicial = { lock: form.modo_bloqueo?.checked, ro: form.solo_lectura?.checked };
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const avisos = [];
                if (form.modo_bloqueo.checked && !inicial.lock) avisos.push('<li><b style="color:var(--sl-red)">LOCKDOWN</b>: solo los niveles ≤ ' + esc(form.bloqueo_nivel_maximo.value) + ' podrán entrar al macro módulo.</li>');
                if (form.solo_lectura.checked && !inicial.ro) avisos.push('<li><b style="color:var(--sl-amber)">SOLO LECTURA</b>: se congelarán todas las operaciones de escritura.</li>');
                if (avisos.length && !(await confirmar('contención', '<p>Vas a activar:</p><ul>' + avisos.join('') + '</ul>', 'Activar'))) return;
                const btn = form.querySelector('[type="submit"]');
                cargando(btn, true);
                try {
                    const r = await api(RUTA.politicasGuardar, new FormData(form));
                    toast(r.message);
                    recargar(900);
                } catch (err) {
                    toast(err.message, true);
                    cargando(btn, false);
                }
            });
        }

        const purga = $('#slFormPurga');
        purga?.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (purga.confirmacion.value.trim() !== 'PURGAR') return toast('Escribe PURGAR (en mayúsculas) para confirmar.', true);
            const dias = Number(purga.dias.value || 0);
            const ok = await confirmar('purgar bitácora',
                '<p>Se eliminarán ' + (dias ? 'los eventos con más de <b>' + dias + '</b> días' : '<b style="color:var(--sl-red)">TODOS</b> los eventos') + ' de la bitácora. Esta acción es irreversible.</p>',
                'Purgar');
            if (!ok) return;
            const btn = purga.querySelector('[type="submit"]');
            cargando(btn, true);
            try {
                const r = await api(RUTA.purgar, new FormData(purga));
                toast(r.message);
                recargar(1200);
            } catch (err) {
                toast(err.message, true);
                cargando(btn, false);
            }
        });
    }

    /* ─────────────────────────────── Arranque ─────────────────────────────── */

    lluviaMatrix();
    tipeo();
    reloj();

    if (CFG.error) return;

    switch (PAGINA) {
        case 'soc':
            initSoc();
            break;
        case 'permisos':
            initSubtabs();
            initMatriz();
            initAutocompletes();
            initSelectoresAccion();
            initExcepciones();
            initSimulador();
            initRutas();
            break;
        case 'amenazas':
            initAmenazas();
            break;
        case 'politicas':
            initPoliticas();
            break;
    }
})();
