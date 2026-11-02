#!/usr/bin/env bash
# ==============================================================================
# INSTALADOR AUTOMÁTICO DEL MACROMÓDULO CIIDI PARA SERVIDORES LINUX (100% OFFLINE)
# Archivo: instalar_macromodulo_linux.sh
# Diseñado para funcionar SIN CONEXIÓN A INTERNET (vía Pendrive / USB).
#
# Uso:
#   sudo bash instalar_macromodulo_linux.sh /var/www/html/proyect_CIIDI
# ==============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TARGET_DIR="${1:-}"

# Auto-detectar dónde está alojado el proyecto CIIDI en el servidor Linux si no se indicó la ruta
if [ -z "$TARGET_DIR" ]; then
    if [ -f "$SCRIPT_DIR/core/System/Kernel.php" ] && [ -f "$SCRIPT_DIR/public/index.php" ]; then
        TARGET_DIR="$SCRIPT_DIR"
    else
        for cand in \
            "/var/www/html/proyect_CIIDI/proyect_CIIDI" \
            "/var/www/html/proyect_CIIDI" \
            "/var/www/proyect_CIIDI" \
            "/var/www/html/ciidi" \
            "/var/www/html"; do
            if [ -f "$cand/core/System/Kernel.php" ]; then
                TARGET_DIR="$cand"
                break
            fi
        done
    fi
fi

if [ -z "$TARGET_DIR" ] || [ ! -f "$TARGET_DIR/core/System/Kernel.php" ]; then
    echo "======================================================================"
    echo " [ATENCIÓN] No se detectó automáticamente la carpeta del sistema CIIDI."
    echo " Por favor indique la ruta donde está instalado el sistema en Linux."
    echo " Ejemplo:"
    echo "   sudo bash instalar_macromodulo_linux.sh /var/www/html/proyect_CIIDI"
    echo "======================================================================"
    exit 1
fi

TARGET_DIR="$(cd "$TARGET_DIR" && pwd)"

echo "======================================================================"
echo " INSTALADOR OFFLINE LINUX - MACROMÓDULO E INTEGRACIÓN CIIDI"
echo "======================================================================"
echo " Carpeta del instalador : $SCRIPT_DIR"
echo " Carpeta del sistema    : $TARGET_DIR"
echo "----------------------------------------------------------------------"

# 1. Crear respaldo preventivo de los archivos actuales del servidor
TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
BACKUP_DIR="$TARGET_DIR/storage/backups"
mkdir -p "$BACKUP_DIR"
BACKUP_FILE="$BACKUP_DIR/pre_macromodulo_backup_${TIMESTAMP}.tar.gz"

echo "[1/4] Creando copia de seguridad previa en:"
echo "      $BACKUP_FILE"
tar -czf "$BACKUP_FILE" -C "$TARGET_DIR" \
    core/ \
    modules/Autenticacion/ \
    modules/Investigaciones/ \
    modules/LineasInvestigacion/ \
    modules/VinculacionEmpresarial/ \
    modules/Cursos/ \
    public/assets/css/style.css 2>/dev/null || true

# 2. Copiar archivos del paquete al servidor
if [ "$SCRIPT_DIR" != "$TARGET_DIR" ]; then
    echo "[2/4] Copiando módulos del Macromódulo, Core, vistas y migraciones..."
    for item in core migrations modules public storage instalar_macromodulo_db.php; do
        if [ -e "$SCRIPT_DIR/$item" ]; then
            cp -r "$SCRIPT_DIR/$item" "$TARGET_DIR/"
        fi
    done
else
    echo "[2/4] Archivos ya ubicados dentro de la carpeta del sistema."
fi

# 3. Ejecutar migraciones de Base de Datos PostgreSQL y activar módulos (100% Offline)
echo "[3/4] Actualizando tablas en PostgreSQL y registrando el Macromódulo..."
if ! command -v php >/dev/null 2>&1; then
    echo "[ERROR] No se encontró el comando 'php'. Verifique que PHP esté instalado."
    exit 1
fi

(cd "$TARGET_DIR" && php instalar_macromodulo_db.php)

# 4. Ajustar permisos de Linux para Apache / Nginx (www-data)
echo "[4/4] Configurando permisos de Linux para el servidor web..."
WEB_USER=""
for candidate in www-data apache nginx http; do
    if id "$candidate" >/dev/null 2>&1; then
        WEB_USER="$candidate"
        break
    fi
done

mkdir -p "$TARGET_DIR/storage/logs" "$TARGET_DIR/storage/backups" "$TARGET_DIR/storage/modelos_ia" "$TARGET_DIR/storage/cache"
chmod -R 775 "$TARGET_DIR/storage" 2>/dev/null || true
chmod +x "$TARGET_DIR/storage/modelos_ia/ml_pipeline.py" 2>/dev/null || true

if [ -n "$WEB_USER" ] && [ "$(id -u)" -eq 0 ]; then
    chown -R "$WEB_USER:$WEB_USER" "$TARGET_DIR/storage" "$TARGET_DIR/modules" "$TARGET_DIR/core" 2>/dev/null || true
    echo "      [OK] Permisos asignados al usuario web '$WEB_USER'."
fi

echo "======================================================================"
echo " ¡INSTALACIÓN OFFLINE COMPLETADA CON ÉXITO!"
echo " Ya puede abrir el sistema en el navegador del servidor."
echo "======================================================================"
