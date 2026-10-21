<?php
// modules/SeguridadLineas/views/permisos.php
// Gestión de permisos: matriz nivel × submódulo × acción, excepciones por usuario, simulador y mapa de rutas.

require __DIR__ . '/_cabecera.php';

if (empty($slError)):
    $rw         = !empty($slPuede['configurar']);
    $niveles    = $niveles ?? [];
    $matriz     = $matriz ?? [];
    $nExplicit  = 0;
    foreach ($matriz as $porSub) foreach ($porSub as $porAcc) $nExplicit += count($porAcc);
    $duraciones = [0 => 'Permanente', 1 => '1 hora', 8 => '8 horas', 24 => '24 horas', 72 => '3 días', 168 => '7 días', 720 => '30 días'];
    $origenTag  = ['manual' => 'is-amber', 'declarada' => 'is-cyan', 'heuristica' => 'is-violet', 'fija' => 'is-green'];
?>

<div class="sl-subtabs" role="tablist">
    <button type="button" class="sl-subtab is-active" data-sl-subtab="matriz"><i class="ph ph-grid-nine"></i>Matriz</button>
    <button type="button" class="sl-subtab" data-sl-subtab="excepciones"><i class="ph ph-user-switch"></i>Excepciones <span class="sl-dim">(<?= count($excepciones ?? []) ?>)</span></button>
    <button type="button" class="sl-subtab" data-sl-subtab="simulador"><i class="ph ph-flask"></i>Simulador</button>
    <button type="button" class="sl-subtab" data-sl-subtab="rutas"><i class="ph ph-signpost"></i>Mapa de rutas <span class="sl-dim">(<?= count($rutasMapa ?? []) ?>)</span></button>
</div>

<!-- ═════════════ MATRIZ ═════════════ -->
<div class="sl-subpanel is-active" data-sl-panel="matriz">
    <div class="sl-card">
        <div class="sl-card-head">
            <span class="sl-card-title"><i class="ph ph-grid-nine"></i>Matriz de control de acceso</span>
            <span class="sl-row">
                <span class="sl-help"><?= $nExplicit ?> celdas explícitas</span>
                <?php if ($rw): ?>
                    <button type="button" class="sl-btn sl-btn-danger sl-btn-sm" data-sl-restablecer><i class="ph ph-arrow-counter-clockwise"></i>Restablecer</button>
                <?php endif; ?>
            </span>
        </div>
        <p class="sl-card-desc">
            Clic en una celda para alternar. Clic en el <b>encabezado de columna</b> o en el <b>nivel</b> para alternar toda la columna/fila del bloque.
            <span style="color:var(--sl-green)">✓</span> permitido · <span style="color:var(--sl-red)">✕</span> denegado · punto gris = valor por defecto (sin regla explícita).
            El nivel 0 es inmutable (anti auto-bloqueo). Por defecto los submódulos de negocio están abiertos y Seguridad cerrado.
        </p>

        <div id="slMatriz">
        <?php foreach ($slSubmodulos as $carpeta => $info): ?>
            <div class="sl-matrix-block" data-sub="<?= sl_e($carpeta) ?>">
                <div class="sl-matrix-title">
                    <i class="<?= sl_e($info['icono'] ?? 'ph ph-cube') ?>"></i>
                    <b><?= sl_e($info['titulo']) ?></b>
                    <span class="sl-badge sl-tag"><?= sl_e($carpeta) ?></span>
                    <?php if ($carpeta === SeguridadMotor::MODULO): ?><span class="sl-badge sl-tag is-red">núcleo de seguridad</span><?php endif; ?>
                    <span class="sl-help"><?= count($info['rutas'] ?? []) ?> rutas</span>
                </div>
                <div class="sl-table-wrap">
                    <table class="sl-table sl-matrix">
                        <thead>
                        <tr>
                            <th>Nivel / Roles</th>
                            <?php foreach ($info['acciones'] as $acc): ?>
                                <th class="sl-col" data-acc="<?= sl_e($acc) ?>" title="<?= sl_e($slAcciones[$acc]['desc']) ?>">
                                    <i class="ph <?= sl_e($slAcciones[$acc]['icono']) ?>"></i><?= sl_e($slAcciones[$acc]['etiqueta']) ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($niveles as $nv => $datosNivel): $locked = $nv === 0; ?>
                            <tr class="<?= $locked ? 'is-locked' : '' ?>" data-nivel="<?= (int) $nv ?>">
                                <td class="sl-lvl" title="<?= $locked ? 'Nivel inmutable' : 'Alternar toda la fila' ?>">
                                    <b><?= sl_e(sl_nivel($nv)) ?></b>
                                    <?php if ($locked): ?> <i class="ph ph-lock-simple" style="color:var(--sl-muted)"></i><?php endif; ?>
                                    <small><?= sl_e($datosNivel['roles'] ?: 'Sin roles') ?><?= $datosNivel['usuarios'] ? ' · ' . (int) $datosNivel['usuarios'] . ' usuario(s)' : '' ?></small>
                                </td>
                                <?php foreach ($info['acciones'] as $acc):
                                    $explicito = $matriz[$nv][$carpeta][$acc] ?? null;
                                    $efectivo  = $locked ? true : ($explicito ?? SeguridadMotor::permisoPorDefecto($carpeta, $acc, (int) $nv));
                                ?>
                                    <td class="sl-cell">
                                        <button type="button"
                                                class="sl-cellbtn <?= $efectivo ? 'is-on' : '' ?> <?= $explicito === null && !$locked ? 'is-default' : '' ?>"
                                                data-nivel="<?= (int) $nv ?>" data-sub="<?= sl_e($carpeta) ?>" data-acc="<?= sl_e($acc) ?>"
                                                data-on="<?= $efectivo ? '1' : '0' ?>" data-orig="<?= $efectivo ? '1' : '0' ?>"
                                                title="<?= sl_e(sl_nivel($nv) . ' · ' . $info['titulo'] . ' · ' . $slAcciones[$acc]['etiqueta']) ?>"
                                                <?= ($locked || !$rw) ? 'disabled' : '' ?>></button>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <?php if ($rw): ?>
            <div class="sl-savebar sl-hidden" id="slSavebar">
                <span><i class="ph ph-warning"></i> <b id="slDirtyCount">0</b> cambio(s) pendientes en la matriz.</span>
                <span class="sl-row">
                    <button type="button" class="sl-btn sl-btn-ghost" data-sl-descartar><i class="ph ph-x"></i>Descartar</button>
                    <button type="button" class="sl-btn" data-sl-guardar-matriz><i class="ph ph-floppy-disk"></i>Aplicar cambios</button>
                </span>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═════════════ EXCEPCIONES ═════════════ -->
<div class="sl-subpanel" data-sl-panel="excepciones">
    <div class="sl-grid sl-grid-main">
        <div class="sl-card">
            <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-list-checks"></i>Excepciones registradas</span></div>
            <p class="sl-card-desc">Tienen prioridad sobre la matriz. Una excepción de <b>denegar</b> siempre gana sobre una de permitir.</p>
            <?php if (empty($excepciones)): ?>
                <div class="sl-empty"><i class="ph ph-user-list"></i>No hay excepciones por usuario.</div>
            <?php else: ?>
                <div class="sl-table-wrap">
                    <table class="sl-table">
                        <thead><tr><th>#</th><th>Usuario</th><th>Efecto</th><th>Ámbito</th><th>Vence</th><th>Motivo</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($excepciones as $ex): ?>
                            <tr class="<?= !empty($ex['expirada']) ? 'is-muted' : '' ?>">
                                <td class="sl-dim">#<?= (int) $ex['id'] ?></td>
                                <td class="sl-nowrap">
                                    <?= sl_e($ex['nombre_completo'] ?? ('ID ' . $ex['usuario_id'])) ?>
                                    <div class="sl-dim">C.I. <?= sl_e($ex['cedula'] ?? '—') ?> · <?= sl_e(sl_nivel($ex['nivel_privilegio'] ?? 999)) ?></div>
                                </td>
                                <td><span class="sl-badge <?= $ex['efecto'] === 'denegar' ? 'sl-res-denegado' : 'sl-res-permitido' ?>"><?= sl_e(strtoupper($ex['efecto'])) ?></span></td>
                                <td class="sl-nowrap"><?= sl_e(sl_sub($ex['submodulo'])) ?> <span class="sl-dim">→</span> <?= sl_e($ex['accion'] === '*' ? 'todas' : ($slAcciones[$ex['accion']]['etiqueta'] ?? $ex['accion'])) ?></td>
                                <td class="sl-nowrap"><?= $ex['expira_en'] ? sl_e($ex['expira_en']) . (!empty($ex['expirada']) ? ' <span class="sl-badge sl-tag">EXPIRADA</span>' : '') : '<span class="sl-dim">∞</span>' ?></td>
                                <td class="sl-trunc sl-dim" title="<?= sl_e($ex['motivo']) ?>"><?= sl_e($ex['motivo']) ?><div class="sl-help">por <?= sl_e($ex['creado_por']) ?></div></td>
                                <td><?php if ($rw): ?><button type="button" class="sl-btn sl-btn-danger sl-btn-sm" data-sl-excepcion-eliminar="<?= (int) $ex['id'] ?>"><i class="ph ph-trash"></i></button><?php endif; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="sl-card">
            <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-user-plus"></i>Nueva excepción</span></div>
            <?php if (!$rw): ?>
                <div class="sl-empty"><i class="ph ph-lock"></i>Requiere permiso «Configurar» en Seguridad.</div>
            <?php else: ?>
                <form class="sl-form" id="slFormExcepcion" autocomplete="off">
                    <div class="sl-field" data-sl-autocomplete="exc">
                        <label>Usuario</label>
                        <input class="sl-input" type="text" data-sl-user-q placeholder="cédula, nombre o correo…">
                        <input type="hidden" name="usuario_id" data-sl-user-id>
                        <div class="sl-suggest sl-hidden" data-sl-suggest></div>
                        <div class="sl-picked sl-hidden" data-sl-picked></div>
                    </div>
                    <div class="sl-form-grid">
                        <div class="sl-field">
                            <label>Submódulo</label>
                            <select class="sl-select" name="submodulo" data-sl-sub-select>
                                <option value="*">★ Todo el macro módulo</option>
                                <?php foreach ($slSubmodulos as $carpeta => $info): ?>
                                    <option value="<?= sl_e($carpeta) ?>" data-acciones="<?= sl_e(implode(',', $info['acciones'])) ?>"><?= sl_e($info['titulo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="sl-field">
                            <label>Acción</label>
                            <select class="sl-select" name="accion" data-sl-acc-select>
                                <option value="*">★ Todas</option>
                                <?php foreach ($slAcciones as $acc => $a): ?>
                                    <option value="<?= sl_e($acc) ?>"><?= sl_e($a['etiqueta']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="sl-form-grid">
                        <div class="sl-field">
                            <label>Efecto</label>
                            <div class="sl-seg">
                                <label><input type="radio" name="efecto" value="permitir" checked><span>Permitir</span></label>
                                <label><input type="radio" name="efecto" value="denegar"><span>Denegar</span></label>
                            </div>
                        </div>
                        <div class="sl-field">
                            <label>Vigencia</label>
                            <select class="sl-select" name="duracion_horas">
                                <?php foreach ($duraciones as $h => $txt): ?>
                                    <option value="<?= $h ?>"><?= $txt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="sl-field">
                        <label>Motivo (queda en la bitácora)</label>
                        <textarea class="sl-textarea" name="motivo" maxlength="500" required placeholder="Ej.: Suspensión preventiva mientras se audita su actividad."></textarea>
                    </div>
                    <button type="submit" class="sl-btn"><i class="ph ph-plus"></i>Registrar excepción</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ═════════════ SIMULADOR ═════════════ -->
<div class="sl-subpanel" data-sl-panel="simulador">
    <div class="sl-grid sl-grid-2">
        <div class="sl-card">
            <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-flask"></i>Simulador de acceso</span></div>
            <p class="sl-card-desc">Evalúa «¿qué pasaría si…?» aplicando exactamente el mismo motor que protege las rutas (IP bloqueada, Lockdown, Solo Lectura, excepciones y matriz). No genera eventos.</p>
            <form class="sl-form" id="slFormSimular" autocomplete="off">
                <?php if ($rw): ?>
                    <div class="sl-field" data-sl-autocomplete="sim">
                        <label>Usuario concreto (opcional)</label>
                        <input class="sl-input" type="text" data-sl-user-q placeholder="buscar usuario… (vacío = usar nivel)">
                        <input type="hidden" name="usuario_id" data-sl-user-id>
                        <div class="sl-suggest sl-hidden" data-sl-suggest></div>
                        <div class="sl-picked sl-hidden" data-sl-picked></div>
                    </div>
                <?php endif; ?>
                <div class="sl-form-grid">
                    <div class="sl-field">
                        <label>Nivel</label>
                        <select class="sl-select" name="nivel">
                            <?php foreach ($niveles as $nv => $d): ?>
                                <option value="<?= (int) $nv ?>" <?= $nv === SeguridadMotor::NIVEL_VISITANTE ? 'selected' : '' ?>><?= sl_e(sl_nivel($nv)) ?> — <?= sl_e(mb_strimwidth($d['roles'] ?: 'Sin roles', 0, 40, '…')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sl-field">
                        <label>IP de origen</label>
                        <input class="sl-input" name="ip" value="<?= sl_e($slCtx['ip']) ?>">
                    </div>
                </div>
                <div class="sl-form-grid">
                    <div class="sl-field">
                        <label>Submódulo</label>
                        <select class="sl-select" name="submodulo" data-sl-sub-select>
                            <?php foreach ($slSubmodulos as $carpeta => $info): ?>
                                <option value="<?= sl_e($carpeta) ?>" data-acciones="<?= sl_e(implode(',', $info['acciones'])) ?>"><?= sl_e($info['titulo']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sl-field">
                        <label>Acción</label>
                        <select class="sl-select" name="accion" data-sl-acc-select>
                            <?php foreach ($slAcciones as $acc => $a): ?>
                                <option value="<?= sl_e($acc) ?>"><?= sl_e($a['etiqueta']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="sl-btn sl-btn-cyan"><i class="ph ph-play"></i>Ejecutar simulación</button>
            </form>
        </div>
        <div class="sl-card">
            <div class="sl-card-head"><span class="sl-card-title"><i class="ph ph-scales"></i>Veredicto</span></div>
            <div id="slSimOut">
                <div class="sl-verdict"><div class="sl-verdict-head" style="color:var(--sl-muted)">EN ESPERA</div><pre>$ sim --run
&gt; configura los parámetros y ejecuta la simulación.</pre></div>
            </div>
            <div class="sl-mt">
                <div class="sl-label">Orden de evaluación</div>
                <pre class="sl-help" style="white-space:pre-wrap;margin:6px 0 0">1. Nivel 0 → PERMITIDO (inmune)
2. IP bloqueada → BLOQUEADO
3. Lockdown → DENEGADO si nivel &gt; máximo
4. Solo Lectura → DENEGADO acciones mutantes
5. Excepción «denegar» del usuario
6. Excepción «permitir» del usuario
7. Matriz nivel × submódulo × acción
8. Valor por defecto</pre>
            </div>
        </div>
    </div>
</div>

<!-- ═════════════ MAPA DE RUTAS ═════════════ -->
<div class="sl-subpanel" data-sl-panel="rutas">
    <div class="sl-card">
        <div class="sl-card-head">
            <span class="sl-card-title"><i class="ph ph-signpost"></i>Clasificación de rutas protegidas</span>
            <div class="sl-input-prompt" style="min-width:260px"><input class="sl-input" id="slFiltroRutas" placeholder="filtrar rutas…"></div>
        </div>
        <p class="sl-card-desc">
            Cada ruta de los submódulos se traduce a una acción de la matriz. Origen:
            <span class="sl-badge sl-tag is-violet">heurística</span> (por el nombre),
            <span class="sl-badge sl-tag is-cyan">declarada</span> (en el módulo),
            <span class="sl-badge sl-tag is-amber">manual</span> (fijada aquí). Uso y denegaciones de los últimos 7 días.
        </p>
        <?php if (empty($rutasMapa)): ?>
            <div class="sl-empty"><i class="ph ph-signpost"></i>No se detectaron rutas en los submódulos.</div>
        <?php else: ?>
            <div class="sl-table-wrap" style="max-height:620px">
                <table class="sl-table" id="slTablaRutas">
                    <thead><tr><th>Ruta</th><th>Submódulo</th><th>Tipo</th><th>Uso 7d</th><th>Denegados</th><th>Origen</th><th>Acción efectiva</th></tr></thead>
                    <tbody>
                    <?php foreach ($rutasMapa as $r): ?>
                        <tr data-ruta="<?= sl_e($r['ruta']) ?>">
                            <td><code><?= sl_e($r['ruta']) ?></code></td>
                            <td class="sl-dim sl-nowrap"><?= sl_e(sl_sub($r['submodulo'])) ?></td>
                            <td><span class="sl-badge sl-tag"><?= sl_e($r['tipo']) ?></span></td>
                            <td><?= (int) $r['uso'] ?></td>
                            <td><?= (int) $r['denegados'] ? '<b style="color:var(--sl-red)">' . (int) $r['denegados'] . '</b>' : '<span class="sl-dim">0</span>' ?></td>
                            <td><span class="sl-badge sl-tag <?= $origenTag[$r['origen']] ?? '' ?>" data-sl-origen><?= sl_e($r['origen']) ?></span></td>
                            <td>
                                <select class="sl-select" data-sl-ruta-select="<?= sl_e($r['ruta']) ?>" style="min-width:170px" <?= $rw ? '' : 'disabled' ?>>
                                    <option value="" <?= $r['origen'] !== 'manual' ? 'selected' : '' ?>>auto (<?= sl_e($r['origen'] === 'manual' ? $r['heuristica'] : $r['accion']) ?>)</option>
                                    <?php foreach ($slAcciones as $acc => $a): ?>
                                        <option value="<?= sl_e($acc) ?>" <?= $r['origen'] === 'manual' && $r['accion'] === $acc ? 'selected' : '' ?>>fijar → <?= sl_e($a['etiqueta']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

<?php require __DIR__ . '/_pie.php'; ?>
