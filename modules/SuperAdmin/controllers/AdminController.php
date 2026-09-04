<?php
// modules/SuperAdmin/controllers/AdminController.php

// IMPORTANTE: Importamos la clase Auth para que el controlador la reconozca
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/AdminDashboardModel.php'; // Incluimos el modelo

class AdminController {
    
    // (Opcional) Si en el futuro necesitas usar AdminUsuarioModel aquí, instáncialo en un constructor
    
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new AdminDashboardModel();
    }

    public function mostrarPanelAdministrativo() {
        Auth::requierePrivilegioMinimo(3);
        
        $datosGraficas = $this->dashboardModel->obtenerEstadisticas();
        $listaTablas = $this->dashboardModel->obtenerTablasSistema(); // <-- MAGIA NUEVA
        
        return [
            'stats' => $datosGraficas,
            'tablas' => $listaTablas // <-- Se lo pasamos a la vista
        ];
    }

    public function generarBackup() {
        // Candado absoluto: Solo el Dios del sistema (Nivel 3) puede descargar la BD entera
        Auth::requierePrivilegioMinimo(3);

        // 1. Credenciales (Mismas que usas en Connection.php)
        $host = 'localhost';
        $port = '5432';
        $db   = 'ciidi';
        $user = 'postgres';
        $pass = '1234'; 

        // 2. Preparamos el destino (storage/backups/)
        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true); // Crea la carpeta si no existe
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "backup_ciidi_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        // 3. Inyectamos la contraseña en la memoria RAM del servidor de forma temporal
        putenv("PGPASSWORD={$pass}");

        // 4. Comando de PostgreSQL
        // ATENCIÓN: Si WAMP no reconoce pg_dump, cambia "pg_dump" por tu ruta absoluta
        $pgDumpPath = '"C:\\Program Files\\PostgreSQL\\18\\bin\\pg_dump.exe"';
        
        $comando = "{$pgDumpPath} -h {$host} -p {$port} -U {$user} -F p --clean --inserts -d {$db} -f \"{$rutaCompleta}\" 2>&1";

        // 5. Ejecutamos el comando en el sistema operativo
        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);

        // 6. Limpiamos la variable de entorno por seguridad inmediatamente
        putenv("PGPASSWORD=");

        // 7. Evaluamos el resultado
        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            // MAGIA NUEVA: En lugar de forzar la descarga, creamos un mensaje de éxito
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['mensaje_admin_exito'] = "Respaldo creado de forma segura en: storage/backups/" . $nombreArchivo;
            
            // Redirigimos de vuelta al panel
            header("Location: sudoadmin");
            exit;
        } else {
            echo "<div style='background:#fee2e2; padding:20px; color:#991b1b; font-family:sans-serif;'>";
            echo "<h2>Error Fatal al generar el Backup</h2>";
            echo "<p>El sistema operativo devolvió el código: <b>{$codigo_retorno}</b></p>";
            echo "<h3>Salida de la consola:</h3>";
            echo "<pre style='background:#fff; padding:10px;'>" . print_r($salida, true) . "</pre>";
            echo "</div>";
            exit;
        }
    }
    public function generarBackupEsquema() {
        Auth::requierePrivilegioMinimo(3);

        $host = 'localhost';
        $port = '5432';
        $db   = 'ciidi';
        $user = 'postgres';
        $pass = 'Wazaaa'; 

        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true);
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "esquema_ciidi_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        putenv("PGPASSWORD={$pass}");

        $pgDumpPath = '"C:\\Program Files\\PostgreSQL\\18\\bin\\pg_dump.exe"';
        
        // LA MAGIA AQUÍ: Añadimos "-s" para extraer solo la estructura (tablas, triggers, foreings) sin la data
        $comando = "{$pgDumpPath} -h {$host} -p {$port} -U {$user} -F p -s -d {$db} -f \"{$rutaCompleta}\" 2>&1";

        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);

        putenv("PGPASSWORD=");

        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Respaldo del ESQUEMA creado en: storage/backups/" . $nombreArchivo;
            header("Location: sudoadmin");
            exit;
        } else {
            echo "Error Fatal al generar el esquema. Código: {$codigo_retorno}<br>";
            echo "<pre>" . print_r($salida, true) . "</pre>";
            exit;
        }
    }

    public function generarBackupTabla() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: sudoadmin");
            exit;
        }

        $tabla = trim($_POST['nombre_tabla']);

        // Filtro de seguridad: Evitar que inyecten comandos malignos en la consola de Windows
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $tabla)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Error: El nombre de la tabla contiene caracteres inválidos.";
            header("Location: sudoadmin");
            exit;
        }

        $host = 'localhost';
        $port = '5432';
        $db   = 'ciidi';
        $user = 'postgres';
        $pass = 'Wazaaa'; 

        $directorioRespaldos = __DIR__ . '/../../../storage/backups/';
        if (!is_dir($directorioRespaldos)) {
            mkdir($directorioRespaldos, 0777, true);
        }
        
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "tabla_{$tabla}_{$fecha}.sql";
        $rutaCompleta = $directorioRespaldos . $nombreArchivo;

        putenv("PGPASSWORD={$pass}");

        $pgDumpPath = '"C:\\Program Files\\PostgreSQL\\18\\bin\\pg_dump.exe"';
        
        // LA MAGIA AQUÍ: Añadimos "-t" seguido del nombre de la tabla
        $comando = "{$pgDumpPath} -h {$host} -p {$port} -U {$user} -F p --clean --inserts -t {$tabla} -d {$db} -f \"{$rutaCompleta}\" 2>&1";

        $salida = [];
        $codigo_retorno = 0;
        exec($comando, $salida, $codigo_retorno);

        putenv("PGPASSWORD=");

        if ($codigo_retorno === 0 && file_exists($rutaCompleta)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Respaldo de la TABLA '{$tabla}' creado en: storage/backups/" . $nombreArchivo;
            header("Location: sudoadmin");
            exit;
        } else {
            // En lugar de "romper" la pantalla, creamos una alerta roja elegante
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_error'] = "Fallo interno al exportar la tabla '{$tabla}'. Verifique los permisos o los logs.";
            header("Location: sudoadmin");
            exit;
        }
    }
    public function alternarMantenimiento() {
    Auth::requierePrivilegioMinimo(3);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $archivo = __DIR__ . '/../../../storage/maintenance.json';
        $actual = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : ['activo' => false];
        $nuevoEstado = !$actual['activo'];
        $mensaje = trim($_POST['mensaje'] ?? '');

        $data = ['activo' => $nuevoEstado, 'mensaje' => $mensaje];
        file_put_contents($archivo, json_encode($data, JSON_PRETTY_PRINT));

        // Si se activa el mantenimiento, cerrar sesiones de no administradores
        if ($nuevoEstado) {
            $this->cerrarSesionesNoAdmin();
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['mensaje_admin_exito'] = $nuevoEstado
            ? "Modo Mantenimiento ACTIVADO. Los estudiantes no podrán acceder."
            : "Modo Mantenimiento DESACTIVADO. Sistema abierto.";

        header("Location: sudoadmin");
        exit;
    }
}

private function cerrarSesionesNoAdmin() {
    // Obtener la ruta donde PHP guarda las sesiones
    $savePath = session_save_path();
    if (empty($savePath)) {
        $savePath = ini_get('session.save_path');
    }
    if (empty($savePath)) {
        $savePath = sys_get_temp_dir();
    }

    // Escanear todos los archivos de sesión
    $archivos = glob($savePath . '/sess_*');
    foreach ($archivos as $archivo) {
        $contenido = file_get_contents($archivo);
        // Buscar el nivel de privilegio en el contenido serializado
        preg_match('/nivel_privilegio\|i:(\d+)/', $contenido, $matches);
        if (isset($matches[1])) {
            $nivel = (int) $matches[1];
            if ($nivel < 3) {
                @unlink($archivo); // Eliminar sesión de no administrador
            }
        }
    }
}
public function restaurarBackup() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] === UPLOAD_ERR_OK) {
                
                $archivoTemporal = $_FILES['backup_file']['tmp_name'];
                $nombreArchivo = $_FILES['backup_file']['name'];
                
                if (strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION)) !== 'sql') {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['mensaje_admin_error'] = "El archivo debe tener formato .sql";
                    header('Location: sudoadmin');
                    exit;
                }

                // 1. Credenciales (Mismas que usas para generar el backup)
                $host = 'localhost';
                $port = '5432';
                $db   = 'ciidi';
                $user = 'postgres';
                $pass = 'Wazaaa'; 

                // 2. Ruta al ejecutable de psql (el hermano de pg_dump que sirve para importar)
                $psqlPath = '"C:\\Program Files\\PostgreSQL\\18\\bin\\psql.exe"';

                // 3. Inyectamos la contraseña temporalmente en el entorno
                putenv("PGPASSWORD={$pass}");

                // 4. Armamos el comando de restauración (usando el archivo temporal que subió PHP)
                // El parámetro -f indica el archivo a leer. El -q es para modo silencioso (menos logs).
                $comando = "{$psqlPath} -h {$host} -p {$port} -U {$user} -q -d {$db} -f \"{$archivoTemporal}\" 2>&1";

                $salida = [];
                $codigo_retorno = 0;
                
                // 5. Ejecutamos la restauración nativa
                exec($comando, $salida, $codigo_retorno);

                // 6. Limpiamos la contraseña inmediatamente
                putenv("PGPASSWORD=");

                if (session_status() === PHP_SESSION_NONE) session_start();

                // 7. Evaluamos el resultado nativo
                if ($codigo_retorno === 0) {
                    $_SESSION['mensaje_admin_exito'] = "Base de datos restaurada con éxito desde el archivo: " . htmlspecialchars($nombreArchivo);
                } else {
                    // Si ocurre un error, guardamos la salida de la consola para poder diagnosticarlo
                    $errorMsg = isset($salida[0]) ? $salida[0] : 'Error desconocido de psql.';
                    $_SESSION['mensaje_admin_error'] = "Fallo nativo al restaurar (Cód $codigo_retorno): " . $errorMsg;
                }
                
            } else {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['mensaje_admin_error'] = "No se subió ningún archivo o superó el tamaño límite de PHP.";
            }
        }
        
        header('Location: sudoadmin');
        exit;
    }
    
    
}
?>