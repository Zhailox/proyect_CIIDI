@echo off
chcp 65001 >nul
setlocal EnableDelayedExpansion

echo ======================================================================
echo  INSTALADOR OFFLINE WINDOWS - MACROMODULO E INTEGRACION CIIDI
echo ======================================================================

set "SCRIPT_DIR=%~dp0"
if "%SCRIPT_DIR:~-1%"=="\" set "SCRIPT_DIR=%SCRIPT_DIR:~0,-1%"

set "TARGET_DIR=%~1"

:: 1. Auto-detectar carpeta del sistema CIIDI en Windows (WAMP / XAMPP / Laragon)
if "%TARGET_DIR%"=="" (
    if exist "%SCRIPT_DIR%\core\System\Kernel.php" if exist "%SCRIPT_DIR%\public\index.php" (
        set "TARGET_DIR=%SCRIPT_DIR%"
    )
)

if "%TARGET_DIR%"=="" (
    for %%D in (
        "C:\wamp64\www\proyect_CIIDI\proyect_CIIDI"
        "C:\wamp64\www\proyect_CIIDI"
        "C:\wamp\www\proyect_CIIDI\proyect_CIIDI"
        "C:\wamp\www\proyect_CIIDI"
        "C:\xampp\htdocs\proyect_CIIDI\proyect_CIIDI"
        "C:\xampp\htdocs\proyect_CIIDI"
        "C:\laragon\www\proyect_CIIDI\proyect_CIIDI"
        "C:\laragon\www\proyect_CIIDI"
    ) do (
        if "!TARGET_DIR!"=="" (
            if exist "%%~D\core\System\Kernel.php" (
                set "TARGET_DIR=%%~D"
            )
        )
    )
)

:ASK_TARGET
if "%TARGET_DIR%"=="" (
    echo.
    echo [ATENCION] No se detecto automaticamente la carpeta del proyecto CIIDI.
    echo Escriba o pegue la ruta completa donde esta instalado el proyecto
    echo ^(Ejemplo: C:\wamp64\www\proyect_CIIDI\proyect_CIIDI^)
    set /p "TARGET_DIR=Ruta del proyecto: "
)

:: Quitar comillas si el usuario las puso
set "TARGET_DIR=%TARGET_DIR:"=%"
if "%TARGET_DIR:~-1%"=="\" set "TARGET_DIR=%TARGET_DIR:~0,-1%"

if not exist "%TARGET_DIR%\core\System\Kernel.php" (
    echo.
    echo [ERROR] No se encontro "core\System\Kernel.php" dentro de:
    echo         "%TARGET_DIR%"
    set "TARGET_DIR="
    goto ASK_TARGET
)

echo  Carpeta del instalador : %SCRIPT_DIR%
echo  Carpeta del sistema    : %TARGET_DIR%
echo ----------------------------------------------------------------------

:: 2. Auto-detectar ejecutable php.exe (PATH, WAMP64, WAMP, XAMPP, Laragon)
set "PHP_EXE="
where php >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    for /f "delims=" %%P in ('where php') do (
        if "!PHP_EXE!"=="" set "PHP_EXE=%%P"
    )
)

if "%PHP_EXE%"=="" (
    for /d %%P in ("C:\wamp64\bin\php\php*") do (
        if exist "%%~P\php.exe" set "PHP_EXE=%%~P\php.exe"
    )
)
if "%PHP_EXE%"=="" (
    for /d %%P in ("C:\wamp\bin\php\php*") do (
        if exist "%%~P\php.exe" set "PHP_EXE=%%~P\php.exe"
    )
)
if "%PHP_EXE%"=="" (
    if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"
)
if "%PHP_EXE%"=="" (
    for /d %%P in ("C:\laragon\bin\php\php*") do (
        if exist "%%~P\php.exe" set "PHP_EXE=%%~P\php.exe"
    )
)

:ASK_PHP
if "%PHP_EXE%"=="" (
    echo.
    echo [ATENCION] No se detecto php.exe automaticamente.
    echo Escriba la ruta completa a php.exe ^(Ejemplo: C:\wamp64\bin\php\php8.3.0\php.exe^)
    set /p "PHP_EXE=Ruta de php.exe: "
    set "PHP_EXE=!PHP_EXE:"=!"
    if not exist "!PHP_EXE!" (
        echo [ERROR] No existe "!PHP_EXE!".
        set "PHP_EXE="
        goto ASK_PHP
    )
)

echo  Ejecutable PHP         : %PHP_EXE%
echo ----------------------------------------------------------------------

:: 3. Crear respaldo preventivo antes de copiar archivos
set "STAMP=%DATE:~-4%%DATE:~3,2%%DATE:~0,2%_%TIME:~0,2%%TIME:~3,2%%TIME:~6,2%"
set "STAMP=%STAMP: =0%"
set "STAMP=%STAMP:/=%"
set "STAMP=%STAMP::=%"
set "BACKUP_DIR=%TARGET_DIR%\storage\backups\pre_macromodulo_win_%STAMP%"

echo [1/3] Creando copia de seguridad previa en:
echo       %BACKUP_DIR%
mkdir "%BACKUP_DIR%" >nul 2>nul
if exist "%TARGET_DIR%\core" xcopy "%TARGET_DIR%\core" "%BACKUP_DIR%\core\" /E /I /Q /Y >nul 2>nul
if exist "%TARGET_DIR%\modules\Autenticacion" xcopy "%TARGET_DIR%\modules\Autenticacion" "%BACKUP_DIR%\modules\Autenticacion\" /E /I /Q /Y >nul 2>nul
if exist "%TARGET_DIR%\public\assets\css\style.css" copy /Y "%TARGET_DIR%\public\assets\css\style.css" "%BACKUP_DIR%\" >nul 2>nul

:: 4. Copiar archivos del Macromodulo y Core si se ejecuta desde otra carpeta
if /I not "%SCRIPT_DIR%"=="%TARGET_DIR%" (
    echo [2/3] Copiando modulos del Macromodulo, Core, vistas y migraciones...
    for %%F in (core migrations modules public storage) do (
        if exist "%SCRIPT_DIR%\%%F" (
            xcopy "%SCRIPT_DIR%\%%F" "%TARGET_DIR%\%%F\" /E /I /Q /Y >nul
        )
    )
    if exist "%SCRIPT_DIR%\instalar_macromodulo_db.php" (
        copy /Y "%SCRIPT_DIR%\instalar_macromodulo_db.php" "%TARGET_DIR%\instalar_macromodulo_db.php" >nul
    )
) else (
    echo [2/3] Ejecucion directa dentro de la carpeta del sistema detectada.
)

:: 5. Ejecutar instalador de Base de Datos PostgreSQL y configuracion
echo [3/3] Ejecutando migraciones en PostgreSQL y activando el Macromodulo...
echo.
pushd "%TARGET_DIR%"
"%PHP_EXE%" "%TARGET_DIR%\instalar_macromodulo_db.php"
set "DB_EXIT=%ERRORLEVEL%"
popd

echo.
if %DB_EXIT% EQU 0 (
    echo ======================================================================
    echo  INSTALACION EN WINDOWS COMPLETADA CON EXITO
    echo  Ya puede abrir el sistema en su navegador web ^(http://localhost/...^).
    echo ======================================================================
) else (
    echo ======================================================================
    echo  [AVISO] Revise los mensajes anteriores ^(verifique que el servicio
    echo  de PostgreSQL y WAMP/XAMPP esten encendidos y vuelva a ejecutar^).
    echo ======================================================================
)

echo.
pause
