<?php
// Solicitamos los datos limpios a la clase de Seguridad
require_once CORE_PATH . 'Security/Auth.php';
$usuarioHeader = Auth::usuario();

// Validar si es Super Administrador (Nivel 0)
$nivelUsuarioHeader = (int)($usuarioHeader['nivel'] ?? 999);
$esAdminTotalHeader = ($nivelUsuarioHeader === 0);

// Lógica de presentación con fallback múltiple de claves de sesión
$nombreCompleto = $usuarioHeader['nombre'] 
    ?? $_SESSION['nombre'] 
    ?? $_SESSION['usuario_nombre'] 
    ?? $_SESSION['nombre_completo'] 
    ?? $_SESSION['usuario'] 
    ?? $_SESSION['emerg_user_nombre'] 
    ?? 'Usuario';

$primerNombreHeader = explode(' ', trim($nombreCompleto))[0];
if (empty($primerNombreHeader)) $primerNombreHeader = 'Usuario';
$inicialHeader = strtoupper(substr($primerNombreHeader, 0, 1));

$rolUsuarioHeader = $usuarioHeader['rol'] 
    ?? $_SESSION['rol'] 
    ?? $_SESSION['rol_nombre'] 
    ?? $_SESSION['usuario_rol'] 
    ?? 'Usuario';

// Resolver foto de perfil en formato WebP para el widget del header
$fotoRawHeader = trim((string)($usuarioHeader['foto_perfil'] ?? ($_SESSION['foto_perfil'] ?? '')));
if (($fotoRawHeader === '' || !str_ends_with(strtolower($fotoRawHeader), '.webp')) && !empty($_SESSION['usuario_id'])) {
    try {
        require_once CORE_PATH . 'Database/Connection.php';
        $pdoHeader = Connection::getInstance();
        $stmtFotoHdr = $pdoHeader->prepare("SELECT foto_perfil FROM usuarios WHERE id = ? LIMIT 1");
        $stmtFotoHdr->execute([(int)$_SESSION['usuario_id']]);
        $fotoDbHdr = trim((string)($stmtFotoHdr->fetchColumn() ?: ''));
        if ($fotoDbHdr !== '') {
            $fotoRawHeader = $fotoDbHdr;
            $_SESSION['foto_perfil'] = $fotoRawHeader;
        }
    } catch (Throwable) {}
}

if ($fotoRawHeader !== '' && !preg_match('#^https?://#i', $fotoRawHeader) && !str_starts_with($fotoRawHeader, 'data:image/') && !str_ends_with(strtolower($fotoRawHeader), '.webp') && function_exists('imagewebp')) {
    $rootHdr = defined('BASE_PATH') ? rtrim(BASE_PATH, '/\\') : dirname(__DIR__, 3);
    $cleanRelHdr = ltrim(str_replace('\\', '/', $fotoRawHeader), '/');
    $absHdr = $rootHdr . '/' . (str_starts_with($cleanRelHdr, 'public/') ? $cleanRelHdr : 'public/' . $cleanRelHdr);
    $webpAbsHdr = preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $absHdr);
    $webpRelHdr = preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $cleanRelHdr);
    if ($webpAbsHdr && $webpRelHdr && is_file($absHdr)) {
        if (!is_file($webpAbsHdr)) {
            $mimeHdr = @mime_content_type($absHdr) ?: '';
            $srcImgHdr = match ($mimeHdr) {
                'image/jpeg' => @imagecreatefromjpeg($absHdr),
                'image/png'  => @imagecreatefrompng($absHdr),
                'image/gif'  => @imagecreatefromgif($absHdr),
                default      => false,
            };
            if ($srcImgHdr) {
                $wH = imagesx($srcImgHdr);
                $hH = imagesy($srcImgHdr);
                $cvH = imagecreatetruecolor($wH, $hH);
                $bgH = imagecolorallocate($cvH, 255, 255, 255);
                imagefill($cvH, 0, 0, $bgH);
                imagecopy($cvH, $srcImgHdr, 0, 0, 0, 0, $wH, $hH);
                imagedestroy($srcImgHdr);
                @imagewebp($cvH, $webpAbsHdr, 85);
                imagedestroy($cvH);
            }
        }
        if (is_file($webpAbsHdr)) {
            $fotoRawHeader = $webpRelHdr;
            $_SESSION['foto_perfil'] = $webpRelHdr;
            if (!empty($_SESSION['usuario_id'])) {
                try {
                    require_once CORE_PATH . 'Database/Connection.php';
                    $stmtUpdWebp = Connection::getInstance()->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
                    $stmtUpdWebp->execute([$webpRelHdr, (int)$_SESSION['usuario_id']]);
                } catch (Throwable) {}
            }
        }
    }
}

$fotoSrcHeader = '';
if ($fotoRawHeader !== '') {
    if (preg_match('#^https?://#i', $fotoRawHeader) || str_starts_with($fotoRawHeader, 'data:image/')) {
        $fotoSrcHeader = $fotoRawHeader;
    } else {
        $fotoSrcHeader = ltrim(preg_replace('#^public/#i', '', str_replace('\\', '/', $fotoRawHeader)), '/');
    }
}

// Rescatar los items del SuperAdmin (Usamos la variable $menu_dinamico del Dispatcher)
$adminItems = [];
if ($esAdminTotalHeader && !empty($menu_dinamico)) {
    foreach ($menu_dinamico as $item) {
        if (($item['modulo_origen'] ?? '') === 'SuperAdmin') {
            $adminItems[] = $item;
        }
    }
}
?>

<?php if ($esAdminTotalHeader): ?>
    <!-- ============================================================== -->
    <!-- MODO SUPER ADMIN: WIDGET DESPLEGABLE CON OPCIONES DEL SISTEMA  -->
    <!-- ============================================================== -->
    <div class="profile-widget-container">
        
        <!-- GATILLO DEL DESPLEGABLE -->
        <div class="header-profile-widget" onclick="toggleProfileDropdown(event)" style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;" title="Opciones de sistema">
            <div class="header-avatar-mini">
                <?php if ($fotoSrcHeader !== ''): ?>
                    <img src="<?= htmlspecialchars($fotoSrcHeader) ?>" alt="<?= htmlspecialchars($primerNombreHeader) ?>" class="header-avatar-img" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <span style="display:none; width:100%; height:100%; align-items:center; justify-content:center;"><?= htmlspecialchars($inicialHeader) ?></span>
                <?php else: ?>
                    <?= htmlspecialchars($inicialHeader) ?>
                <?php endif; ?>
            </div>
            <div class="header-user-info">
                <span class="header-user-name"><?= htmlspecialchars($primerNombreHeader) ?></span>
                <span class="header-user-role"><?= htmlspecialchars($rolUsuarioHeader) ?></span>
            </div>
            <i class="ph-bold ph-caret-down text-muted" id="profile-caret" style="font-size: 0.8rem; margin-left: 0.2rem; transition: transform 0.2s;"></i>
        </div>

        <!-- MENÚ DESPLEGABLE FLOTANTE -->
        <div id="profileDropdown" class="profile-dropdown-menu">
            <a href="perfil" class="profile-dropdown-main">
                <i class="ph-bold ph-user-circle" style="font-size: 1.2rem; color: var(--color-principal);"></i> Mi Perfil
            </a>

            <?php if (!empty($adminItems)): ?>
                <div class="profile-dropdown-header">
                    Administración del Sistema
                </div>
                
                <div class="profile-dropdown-scroll">
                    <?php foreach ($adminItems as $admItem): ?>
                        <?php 
                        $icono_clase = $admItem['icono'] ?? '';
                        $icono_html = strpos($icono_clase, '<i') !== false ? $icono_clase : '<i class="' . $icono_clase . '"></i>';
                        ?>
                        
                        <?php if (($admItem['tipo'] ?? '') === 'link'): ?>
                            <a href="<?= htmlspecialchars($admItem['enlace']) ?>" class="profile-dropdown-item">
                                <?= $icono_html ?> <?= htmlspecialchars($admItem['titulo']) ?>
                            </a>
                        <?php elseif (($admItem['tipo'] ?? '') === 'parent'): ?>
                            <div class="profile-dropdown-parent">
                                <?= $icono_html ?> <?= htmlspecialchars($admItem['titulo']) ?>
                            </div>
                            <?php if (!empty($admItem['subitems'])): ?>
                                <?php foreach ($admItem['subitems'] as $sub): ?>
                                    <a href="<?= htmlspecialchars($sub['ruta']) ?>" class="profile-dropdown-subitem">
                                        <?= htmlspecialchars($sub['titulo']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SCRIPT DE INTERACCIÓN PARA EL DESPLEGABLE -->
    <script>
    function toggleProfileDropdown(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('profileDropdown');
        const caret = document.getElementById('profile-caret');
        dropdown.classList.toggle('show');
        
        if (dropdown.classList.contains('show')) {
            caret.style.transform = 'rotate(180deg)';
        } else {
            caret.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profileDropdown');
        const container = document.querySelector('.profile-widget-container');
        const caret = document.getElementById('profile-caret');
        
        if (dropdown && dropdown.classList.contains('show') && !container.contains(e.target)) {
            dropdown.classList.remove('show');
            caret.style.transform = 'rotate(0deg)';
        }
    });
    </script>

<?php else: ?>
    <!-- ============================================================== -->
    <!-- MODO USUARIO NORMAL: WIDGET TRADICIONAL DE ENLACE DIRECTO      -->
    <!-- ============================================================== -->
    <a href="perfil" class="header-profile-widget" title="Ir a mi perfil">
        <div class="header-avatar-mini">
            <?php if ($fotoSrcHeader !== ''): ?>
                <img src="<?= htmlspecialchars($fotoSrcHeader) ?>" alt="<?= htmlspecialchars($primerNombreHeader) ?>" class="header-avatar-img" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <span style="display:none; width:100%; height:100%; align-items:center; justify-content:center;"><?= htmlspecialchars($inicialHeader) ?></span>
            <?php else: ?>
                <?= htmlspecialchars($inicialHeader) ?>
            <?php endif; ?>
        </div>
        <div class="header-user-info">
            <span class="header-user-name"><?= htmlspecialchars($primerNombreHeader) ?></span>
            <span class="header-user-role"><?= htmlspecialchars($rolUsuarioHeader) ?></span>
        </div>
    </a>
<?php endif; ?>
