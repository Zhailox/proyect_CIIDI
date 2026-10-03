#!/bin/bash
# =============================================================================
# Script de sincronización Git para el proyecto CIIDI
# Nombre: miki.sh
# Ubicación: raíz del proyecto
# Descripción:
#   - Menú interactivo para actualizar desde main (reset o merge) y subir cambios.
#   - Log local por usuario (sync_<nombre>.log) ignorado por Git.
#   - Arte ASCII "miki".
#   - Manejo de conflictos con merge automático en push.
# =============================================================================

# -----------------------------------------------------------------------------
# Colores y estilo
# -----------------------------------------------------------------------------
RESET="\e[0m"
BOLD="\e[1m"
GREEN="\e[32m"
RED="\e[31m"
YELLOW="\e[33m"
CYAN="\e[36m"
MAGENTA="\e[35m"

# -----------------------------------------------------------------------------
# Configuración inicial
# -----------------------------------------------------------------------------
# Moverse a la raíz del script
cd "$(dirname "$0")" || { echo -e "${RED}No se pudo entrar al directorio del script${RESET}"; exit 1; }

# Obtener nombre de usuario de Git
GIT_USER=$(git config user.name)
if [ -z "$GIT_USER" ]; then
    echo -e "${YELLOW}No tienes configurado tu nombre en Git.${RESET}"
    read -p "Introduce tu nombre: " GIT_USER
    git config user.name "$GIT_USER" || { echo -e "${RED}No se pudo configurar el nombre de Git.${RESET}"; exit 1; }
    echo -e "${GREEN}Nombre '$GIT_USER' guardado en Git.${RESET}"
fi

# Archivo de log local
LOG_FILE="sync_${GIT_USER}.log"

# Función de log
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_FILE"
}

# Asegurarse de que el log exista
if [ ! -f "$LOG_FILE" ]; then
    touch "$LOG_FILE"
    echo "=== Inicio de log para $GIT_USER ===" >> "$LOG_FILE"
fi

# Añadir sync_*.log a .gitignore si no está
if [ -f .gitignore ]; then
    if ! grep -q "sync_.*\.log" .gitignore; then
        echo "sync_*.log" >> .gitignore
        log "Añadido sync_*.log al .gitignore"
        echo -e "${CYAN}Se añadió 'sync_*.log' al .gitignore${RESET}"
    fi
else
    echo "sync_*.log" > .gitignore
    log "Creado .gitignore con sync_*.log"
    echo -e "${CYAN}Creado .gitignore con 'sync_*.log'${RESET}"
fi

# -----------------------------------------------------------------------------
# Función para mostrar arte ASCII "miki"
# -----------------------------------------------------------------------------
mostrar_arte() {
    clear
    echo -e "${MAGENTA}${BOLD}"
    cat <<'EOF'
 __  __ ___ _  ___ 
|  \/  |_ _| |/ / |
| |\/| || || ' /| |
|_|  |_|___|_|\_\|_|
EOF
    echo -e "${RESET}"
    echo -e "${YELLOW}=============================================${RESET}"
    echo -e "${GREEN}  Sincronizador Git del Proyecto CIIDI${RESET}"
    echo -e "${YELLOW}=============================================${RESET}"
    echo -e "  Usuario: ${BOLD}$GIT_USER${RESET}"
    echo -e "  Log: ${BOLD}$LOG_FILE${RESET}"
    echo -e "${YELLOW}=============================================${RESET}"
}

# -----------------------------------------------------------------------------
# Opción 1: Subir cambios
# -----------------------------------------------------------------------------
subir_cambios() {
    log "----- Subir cambios -----"
    echo -e "${BOLD}Subir cambios a main${RESET}"
    echo -e "${CYAN}Archivos modificados:${RESET}"
    git status --short || { echo -e "${RED}Error al ejecutar git status${RESET}"; log "Error git status"; return; }

    read -p "¿Deseas continuar? (s/n): " confirm
    if [[ ! "$confirm" =~ ^[Ss]$ ]]; then
        echo -e "${YELLOW}Cancelado por el usuario.${RESET}"
        log "Usuario canceló subir cambios"
        return
    fi

    # Añadir todos los cambios
    echo -e "${CYAN}Añadiendo cambios...${RESET}"
    git add .
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error al hacer git add${RESET}"
        log "Error en git add"
        return
    fi

    # Pedir mensaje de commit
    read -p "Mensaje de commit: " commit_msg
    if [ -z "$commit_msg" ]; then
        commit_msg="Commit automático de $GIT_USER $(date +'%Y-%m-%d %H:%M:%S')"
        echo -e "${YELLOW}Usando mensaje por defecto: $commit_msg${RESET}"
    fi

    echo -e "${CYAN}Haciendo commit...${RESET}"
    git commit -m "$commit_msg"
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error al hacer commit${RESET}"
        log "Error en commit"
        return
    fi

    echo -e "${CYAN}Subiendo a origin main...${RESET}"
    git push origin main
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Push exitoso.${RESET}"
        log "Push exitoso"
        return
    fi

    # Si falla push, intentar merge con main remoto
    echo -e "${YELLOW}El push fue rechazado. Actualizando con merge desde origin/main...${RESET}"
    log "Push rechazado, iniciando merge"
    git pull --merge origin main
    if [ $? -ne 0 ]; then
        echo -e "${RED}Conflicto durante el merge. Debes resolverlo manualmente.${RESET}"
        log "Conflicto en merge, requiere intervención manual"
        read -p "Pulsa Enter para continuar..." dummy
        return
    fi

    # Reintentar push
    echo -e "${CYAN}Reintentando push...${RESET}"
    git push origin main
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Push exitoso después del merge.${RESET}"
        log "Push exitoso tras merge"
    else
        echo -e "${RED}No se pudo subir los cambios. Revisa el estado del repositorio.${RESET}"
        log "Error en push tras merge"
    fi
    read -p "Pulsa Enter para continuar..." dummy
}

# -----------------------------------------------------------------------------
# Opción 2: Actualizar desde main (reset)
# -----------------------------------------------------------------------------
actualizar_reset() {
    log "----- Actualizar desde main (reset) -----"
    echo -e "${BOLD}Actualizar desde main (reset --hard)${RESET}"
    echo -e "${RED}¡ADVERTENCIA! Se descartarán todos los cambios locales sin commitear.${RESET}"
    read -p "¿Estás seguro? (s/n): " confirm
    if [[ ! "$confirm" =~ ^[Ss]$ ]]; then
        echo -e "${YELLOW}Cancelado.${RESET}"
        log "Reset cancelado"
        return
    fi

    echo -e "${CYAN}Obteniendo últimos cambios de origin...${RESET}"
    git fetch origin
    if [ $? -ne 0 ]; then
        echo -e "${RED}Error al hacer fetch.${RESET}"
        log "Error en fetch"
        return
    fi

    echo -e "${CYAN}Restableciendo a origin/main...${RESET}"
    git reset --hard origin/main
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Actualización completada.${RESET}"
        log "Reset --hard exitoso"
    else
        echo -e "${RED}Error al hacer reset.${RESET}"
        log "Error en reset"
    fi
    read -p "Pulsa Enter para continuar..." dummy
}

# -----------------------------------------------------------------------------
# Opción 3: Actualizar con merge (conservar cambios)
# -----------------------------------------------------------------------------
actualizar_merge() {
    log "----- Actualizar con merge -----"
    echo -e "${BOLD}Actualizar con merge desde origin/main${RESET}"
    echo -e "${CYAN}Se fusionarán los cambios remotos con los locales (si existen).${RESET}"
    git pull --merge origin main
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Merge completado.${RESET}"
        log "Merge exitoso"
    else
        echo -e "${RED}Conflicto durante el merge. Resuélvelo manualmente.${RESET}"
        log "Conflicto en merge (opción manual)"
    fi
    read -p "Pulsa Enter para continuar..." dummy
}

# -----------------------------------------------------------------------------
# Opción 4: Ver estado
# -----------------------------------------------------------------------------
ver_estado() {
    log "----- Ver estado -----"
    echo -e "${BOLD}Estado del repositorio:${RESET}"
    git status
    read -p "Pulsa Enter para continuar..." dummy
}

# -----------------------------------------------------------------------------
# Opción 5: Ver log local
# -----------------------------------------------------------------------------
ver_log() {
    log "----- Ver log local -----"
    if [ -f "$LOG_FILE" ]; then
        echo -e "${BOLD}Contenido de $LOG_FILE:${RESET}"
        cat "$LOG_FILE"
    else
        echo -e "${YELLOW}No hay log local aún.${RESET}"
    fi
    read -p "Pulsa Enter para continuar..." dummy
}

# -----------------------------------------------------------------------------
# Menú principal
# -----------------------------------------------------------------------------
while true; do
    mostrar_arte
    echo -e "${BOLD}MENÚ PRINCIPAL${RESET}"
    echo "1. Subir cambios (commit y push)"
    echo "2. Actualizar desde main (reset)"
    echo "3. Actualizar con merge (conservar cambios)"
    echo "4. Ver estado (git status)"
    echo "5. Ver log local"
    echo "0. Salir"
    echo ""
    read -p "Elige una opción: " opcion
    case $opcion in
        1) subir_cambios ;;
        2) actualizar_reset ;;
        3) actualizar_merge ;;
        4) ver_estado ;;
        5) ver_log ;;
        0) 
            log "Salida del script"
            echo -e "${GREEN}¡Hasta luego!${RESET}"
            exit 0
            ;;
        *) 
            echo -e "${RED}Opción inválida${RESET}"
            sleep 1
            ;;
    esac
done