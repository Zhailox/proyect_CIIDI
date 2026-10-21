#!/bin/bash
# =============================================================================
# Panel de Control Maestro - El Monstruo (CIIDI)
# =============================================================================

# Variables del Proyecto
REPO_URL="https://github.com/Zhailox/proyect_CIIDI.git"
BRANCH="main"
PROJECT_DIR="/var/www/html/proyect_CIIDI"
DB_USER="miki"
DB_PASS="1234"
DB_NAME="ciidi"
SQL_FILE="$PROJECT_DIR/ciidi.sql"
SERVICIOS=("apache2" "mariadb" "postgresql" "llama-server" "ufw" "ssh" "cloudflared")
USUARIO="${SUDO_USER:-$(logname 2>/dev/null || echo root)}"

# Colores
RESET="\e[0m"
BOLD="\e[1m"
GREEN="\e[32m"
RED="\e[31m"
YELLOW="\e[33m"
CYAN="\e[36m"
MAGENTA="\e[35m"

# Verificación de root
if [[ $EUID -ne 0 ]]; then
    echo -e "${RED}Este script necesita permisos de superusuario. Ejecuta: sudo bash $0${RESET}"
    exit 1
fi

mostrar_arte() {
    clear
    echo -e "${CYAN}${BOLD}"
    cat <<'EOF'
    _    _          _          _         __ _             
   / \  | | ___  __| | __ _   / _| | __ _ ___| |_ __ _ _ __ 
  / _ \ | |/ _ \/ _` |/ _` | | |_  |/ _` / __| __/ _` | '__|
 / ___ \| |  __/ (_| | (_| | |  _| | (_| \__ \ || (_| | |   
/_/   \_\_|\___|\__,_|\__,_| |_|   |_|\__,_|___/\__\__,_|_|   
EOF
    echo -e "${RESET}"
    echo -e "${YELLOW}=============================================================${RESET}"
    echo -e "${GREEN}  Centro de Comando - Servidor CIIDI${RESET}"
    echo -e "${YELLOW}=============================================================${RESET}"
}

# 1. Actualizar Paquetes del Sistema
actualizar_sistema() {
    echo -e "${CYAN}Actualizando repositorios y paquetes de Debian...${RESET}"
    apt update && apt upgrade -y
    echo -e "${GREEN}Actualización del sistema completada.${RESET}"
    read -p "Pulsa Enter para continuar..." dummy
}

# 2. Actualizar Código desde GitHub
actualizar_github() {
    echo -e "${CYAN}Sincronizando con GitHub (Rama: $BRANCH)...${RESET}"
    if [ -d "$PROJECT_DIR/.git" ]; then
        cd "$PROJECT_DIR"
        git fetch origin "$BRANCH"
        git checkout "$BRANCH"
        git pull --ff-only origin "$BRANCH"
    else
        git clone -b "$BRANCH" "$REPO_URL" "$PROJECT_DIR"
    fi
    
    echo -e "${CYAN}Ajustando permisos y dependencias...${RESET}"
    chmod -R 777 "$PROJECT_DIR"
    chown -R "$USUARIO:www-data" "$PROJECT_DIR" 2>/dev/null
    
    if [ -f "$PROJECT_DIR/composer.json" ]; then
        cd "$PROJECT_DIR"
        composer install --no-interaction --no-dev
    fi
    echo -e "${GREEN}Repositorio actualizado correctamente.${RESET}"
    read -p "Pulsa Enter para continuar..." dummy
}

# 3. Importar Base de Datos
importar_bd() {
    echo -e "${RED}${BOLD}¡ADVERTENCIA! Esto borrará la base de datos actual '$DB_NAME' y la recreará desde cero.${RESET}"
    read -p "¿Estás segura de que deseas continuar? (s/n): " confirmacion
    if [[ "$confirmacion" == "s" || "$confirmacion" == "S" ]]; then
        if [ ! -f "$SQL_FILE" ]; then
            echo -e "${RED}Error: No se encontró $SQL_FILE.${RESET}"
        else
            echo -e "${CYAN}Recreando base de datos y asignando permisos a $DB_USER...${RESET}"
            sudo -u postgres dropdb --if-exists "$DB_NAME"
            sudo -u postgres createdb -O "$DB_USER" "$DB_NAME"
            sudo -u postgres psql -d "$DB_NAME" -f "$SQL_FILE"
            
            # Ceder privilegios absolutos para evitar el error de "Acceso Denegado"
            sudo -u postgres psql -d "$DB_NAME" -c "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO $DB_USER;"
            sudo -u postgres psql -d "$DB_NAME" -c "GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO $DB_USER;"
            
            echo -e "${GREEN}Base de datos restaurada y configurada con éxito.${RESET}"
        fi
    else
        echo -e "${YELLOW}Operación cancelada.${RESET}"
    fi
    read -p "Pulsa Enter para continuar..." dummy
}

# Gestión de Servicios
accion_servicio() {
    local svc="$1"
    local accion="$2"
    systemctl "$accion" "$svc" >/dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}[OK]${RESET} $svc -> $accion"
    else
        echo -e "${RED}[ERROR]${RESET} $svc -> $accion falló"
    fi
}

accion_todos() {
    local accion="$1"
    echo -e "${MAGENTA}Aplicando '$accion' a todos los servicios...${RESET}"
    for svc in "${SERVICIOS[@]}"; do
        accion_servicio "$svc" "$accion"
    done
    read -p "Pulsa Enter para continuar..." dummy
}

mostrar_estado() {
    echo -e "${MAGENTA}Estado de los motores del sistema:${RESET}"
    for svc in "${SERVICIOS[@]}"; do
        if systemctl is-active "$svc" >/dev/null 2>&1; then
            echo -e "${GREEN}[ACTIVO]${RESET} $svc"
        else
            echo -e "${RED}[INACTIVO]${RESET} $svc"
        fi
    done
    read -p "Pulsa Enter para continuar..." dummy
}

submenu_individual() {
    while true; do
        clear
        echo -e "${BOLD}${CYAN}Selecciona un servicio aisaldo:${RESET}"
        for i in "${!SERVICIOS[@]}"; do
            echo "$((i+1)). ${SERVICIOS[$i]}"
        done
        echo "0. Volver"
        read -p "Opción: " opc
        if [[ "$opc" == "0" ]]; then return; fi
        if [[ "$opc" -gt 0 && "$opc" -le "${#SERVICIOS[@]}" ]]; then
            svc="${SERVICIOS[$((opc-1))]}"
            echo -e "\n${BOLD}Servicio: $svc${RESET} -> 1) Iniciar 2) Detener 3) Reiniciar"
            read -p "Acción: " accion_num
            case $accion_num in
                1) accion_servicio "$svc" "start" ;;
                2) accion_servicio "$svc" "stop" ;;
                3) accion_servicio "$svc" "restart" ;;
            esac
            read -p "Pulsa Enter para continuar..." dummy
        fi
    done
}

# Menú Principal
while true; do
    mostrar_arte
    echo -e "${BOLD}MANTENIMIENTO DEL SISTEMA${RESET}"
    echo "1. Actualizar paquetes del servidor (apt update)"
    echo "2. Descargar código desde GitHub (git pull)"
    echo "3. Restaurar / Importar Base de Datos (ciidi.sql)"
    echo -e "\n${BOLD}GESTIÓN DE SERVICIOS${RESET}"
    echo "4. Iniciar todos los servicios"
    echo "5. Detener todos los servicios"
    echo "6. Reiniciar todos los servicios"
    echo "7. Gestionar servicio individual (Apache, Postgres, etc.)"
    echo "8. Ver estado general del servidor"
    echo -e "\n0. Salir"
    echo ""
    read -p "Elige una directriz: " opcion
    case $opcion in
        1) actualizar_sistema ;;
        2) actualizar_github ;;
        3) importar_bd ;;
        4) accion_todos "start" ;;
        5) accion_todos "stop" ;;
        6) accion_todos "restart" ;;
        7) submenu_individual ;;
        8) mostrar_estado ;;
        0) echo -e "${GREEN}Cerrando consola de mando...${RESET}"; exit 0 ;;
        *) echo -e "${RED}Directriz inválida${RESET}"; sleep 1 ;;
    esac
done