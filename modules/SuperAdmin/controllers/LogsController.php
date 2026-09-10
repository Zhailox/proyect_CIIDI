<?php
require_once CORE_PATH . 'Security/Auth.php';
require_once __DIR__ . '/../models/LogsModel.php';

class LogsController {
    
    private $logsModel;

    public function __construct() {
        $this->logsModel = new LogsModel();
    }

    public function index() {
        Auth::requierePrivilegioMinimo(3);

        $fNivel       = trim($_GET['nivel'] ?? '');
        $fModulo      = trim($_GET['modulo'] ?? '');
        $fFechaInicio = trim($_GET['fecha_inicio'] ?? '');
        $fFechaFin    = trim($_GET['fecha_fin'] ?? '');

        $auditoriaDB  = $this->logsModel->obtenerAuditoriaDB();
        $accesos      = $this->logsModel->obtenerAccesos();
        $auditTrail   = $this->logsModel->obtenerAuditTrail($fNivel, $fModulo, $fFechaInicio, $fFechaFin);

        return [
            'logs_db'      => $auditoriaDB,
            'logs_auth'    => $accesos,
            'audit_trail'  => $auditTrail,
            'f_nivel'      => $fNivel,
            'f_modulo'     => $fModulo,
            'f_fecha_init' => $fFechaInicio,
            'f_fecha_fin'  => $fFechaFin
        ];
    }

    public function exportarLogs() {
        Auth::requierePrivilegioMinimo(3);

        $formato      = trim($_GET['formato'] ?? 'csv'); // csv | pdf
        $fNivel       = trim($_GET['nivel'] ?? '');
        $fModulo      = trim($_GET['modulo'] ?? '');
        $fFechaInicio = trim($_GET['fecha_inicio'] ?? '');
        $fFechaFin    = trim($_GET['fecha_fin'] ?? '');

        $logs = $this->logsModel->obtenerAuditTrail($fNivel, $fModulo, $fFechaInicio, $fFechaFin);
        $fecha = date('Y-m-d_H-i');

        if ($formato === 'pdf') {
            // Generación de reporte HTML imprimible en PDF
            header('Content-Type: text/html; charset=UTF-8');
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Reporte de Auditoría - CIIDI</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 20px; }
                    h2 { color: #002244; margin-bottom: 5px; }
                    .meta { color: #64748b; font-size: 11px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
                    th { background-color: #f1f5f9; color: #334155; }
                    .badge { font-weight: bold; padding: 2px 6px; border-radius: 4px; font-size: 10px; }
                    .INFO { color: #0369a1; background: #e0f2fe; }
                    .WARNING { color: #b45309; background: #fef3c7; }
                    .CRITICAL, .ERROR { color: #b91c1c; background: #fee2e2; }
                </style>
            </head>
            <body onload="window.print()">
                <h2>REPORTE DE AUDITORÍA Y MONITOREO DEL SISTEMA</h2>
                <div class="meta">Generado por el SuperAdmin el <?= date('d/m/Y H:i:s') ?> | UPTTMBI - CIIDI</div>

                <table>
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Nivel</th>
                            <th>Módulo</th>
                            <th>Acción Realizada</th>
                            <th>Detalles</th>
                            <th>Responsable</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td><?= htmlspecialchars($l['fecha_hora']) ?></td>
                                <td><span class="badge <?= $l['nivel'] ?>"><?= htmlspecialchars($l['nivel']) ?></span></td>
                                <td><?= htmlspecialchars($l['modulo']) ?></td>
                                <td><strong><?= htmlspecialchars($l['accion']) ?></strong></td>
                                <td><?= htmlspecialchars($l['detalles']) ?></td>
                                <td><?= htmlspecialchars($l['responsable']) ?></td>
                                <td><?= htmlspecialchars($l['ip']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </body>
            </html>
            <?php
            exit;
        } else {
            // Exportación estándar en CSV
            header('Content-Type: text/csv; charset=UTF-8');
            header("Content-Disposition: attachment; filename=auditoria_ciidi_{$fecha}.csv");
            
            $output = fopen('php://output', 'w');
            // BOM UTF-8 para Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, ['ID', 'Fecha Hora', 'Nivel', 'Modulo', 'Accion', 'Detalles', 'Responsable', 'IP']);

            foreach ($logs as $l) {
                fputcsv($output, [
                    $l['id'],
                    $l['fecha_hora'],
                    $l['nivel'],
                    $l['modulo'],
                    $l['accion'],
                    $l['detalles'],
                    $l['responsable'],
                    $l['ip']
                ]);
            }
            fclose($output);
            exit;
        }
    }

    public function limpiarLogs() {
        Auth::requierePrivilegioMinimo(3);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logsModel->limpiarLogsAudit();
            AuditLogger::registrar('WARNING', 'SuperAdmin', 'Limpiar Auditoría', 'Se depuraron los registros de auditoría activa de almacenamiento.');
            
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['mensaje_admin_exito'] = "Los registros de auditoría activa fueron vaciados correctamente.";
            header("Location: visor-logs");
            exit;
        }
    }
}